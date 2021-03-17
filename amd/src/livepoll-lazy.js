// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Live poll main module.
 *
 * @package mod_livepoll
 * @copyright Copyright (c) 2018 Open LMS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(["jquery", "core/log", "core/templates"],
    function($, Log, templates) {

        var self = this;

        /**
         * Resets the vote count for each option to 0.
         */
        var resetVotes = function() {
            self.votes = [];
            $.each(self.options, function(optionid) {
                self.votes[optionid] = 0;
            });
        };

        /**
         * Adds the click listener to each vote btn so the vote firebase db is updated.
         */
        var addClickListeners = function() {
            if (self.listeningToClicks) {
                return;
            }
            $(".livepoll-votebtn").on("click", function(){
                var option = $(this).data("option");
                var vote = {
                    option: option
                };
                var voteRef = self.database.ref("polls/" + self.pollKey + "/votes/" + self.userKey);
                voteRef.once("value").then(function(voteSnapshot) {
                    if (voteSnapshot.val() && voteSnapshot.val().option === vote.option) {
                        voteRef.remove();
                    } else {
                        voteRef.set(vote);
                    }
                });
            });

            self.listeningToClicks = true;
        };

        var addControlListeners = function() {
            $("#livepoll_closevoting").on("change", function() {
                var closeVoting = this.checked;
                var controlRef = self.database.ref("polls/" + self.pollKey + "/controls/closeVoting");
                controlRef.set(closeVoting);
            });

            $("#livepoll_highlightanswer").on("change", function() {
                var higlightAnswer = this.checked;
                var controlRef = self.database.ref("polls/" + self.pollKey + "/controls/higlightAnswer");
                controlRef.set(higlightAnswer);
            });

            // Enable voting as default behaviour.
            if (!self.closeVoting) {
                $(".livepoll-votebtn").removeClass("disabled");
                addClickListeners();
            }
        };

        var removeClickListeners = function() {
            if (!self.listeningToClicks) {
                return;
            }

            $(".livepoll-votebtn").off("click");
            self.listeningToClicks = false;
        };

        /**
         * Updates the vote UI.
         * Chart and text vote count.
         */
        var updateVoteUI = function() {
            var promises = [];
            $.each(self.resultHandlers, function(i, handler) {
                var promise = handler.update(self.options, self.votes);
                promises.push(promise);
            });
            $.when.apply($, promises).done(function() {
                Log.debug("livepoll UI has been updated.");
            });
        };

        /**
         * Updates the voute count and vote UI for a poll snapshot.
         */
        var updateVoteCount = function() {
            var votesRef = self.database.ref("polls/" + self.pollKey + "/votes");
            votesRef.once("value").then(function(votesSnapshot) {
                var votes = votesSnapshot.val();
                resetVotes();
                $(".livepoll-votebtn").addClass("btn-primary").removeClass("btn-success");
                $.each(votes, function( userKey, vote ) {
                    self.votes[vote.option]++;
                    if (userKey === self.userKey) {
                        $(".livepoll-votebtn[data-option=\"" + vote.option + "\"]")
                            .addClass("btn-success").removeClass("btn-primary");
                    }
                });
                updateVoteUI();
            });
        };

        /**
         * Upadtes control input state.
         */
        var updateControls = function() {
            var controlsRef = self.database.ref("polls/" + self.pollKey + "/controls");

            controlsRef.once("value").then(function(controlsSnapshot) {
                var controlStatus = controlsSnapshot.val();

                self.closeVoting = !!controlStatus.closeVoting;
                self.higlightAnswer = !!controlStatus.higlightAnswer;

                $("#livepoll_closevoting").prop('checked', self.closeVoting);
                $("#livepoll_highlightanswer").prop('checked', self.higlightAnswer);

                $(".livepoll-votebtn").toggleClass("disabled", self.closeVoting);


                if (self.closeVoting) {
                    removeClickListeners();
                    var tmpltName = "mod_livepoll/voting_closed";
                    if ($(".livepoll-closed-voting-msg > .alert").length === 0) {
                        templates.render(tmpltName, {}).then(function(html, js) {
                            templates.appendNodeContents(".livepoll-closed-voting-msg", html, js);
                            $(".livepoll-closed-voting-msg > .alert")
                                .alert().removeClass("hide").addClass("show");
                        });
                    }
                } else {
                    addClickListeners();
                    if ($(".livepoll-closed-voting-msg > .alert").length > 0) {
                        $(".livepoll-closed-voting-msg > .alert")
                            .alert("close");
                    }
                }

                $(".livepoll-votebtn").removeClass("livepoll-answer-animation");
                $(".mod-livepoll-text-result").removeClass("livepoll-answer-animation");
                if (self.higlightAnswer) {
                    $(".livepoll-votebtn[data-option=\"" + self.correctOption + "\"]")
                        .addClass("livepoll-answer-animation");
                    $(".mod-livepoll-text-result:has(#vote-count-" + self.correctOption + ")")
                        .addClass("livepoll-answer-animation");
                }
            });
        };

        /**
         * Adds listeners for state changes in the poll.
         */
        var addDBListeners = function() {
            var votesRef = self.database.ref("polls/" + self.pollKey + "/votes");
            votesRef.on("child_added", updateVoteCount);
            votesRef.on("child_changed", updateVoteCount);
            votesRef.on("child_removed", updateVoteCount);

            var controlsRef = self.database.ref("polls/" + self.pollKey + "/controls");
            controlsRef.on("child_added", updateControls);
            controlsRef.on("child_changed", updateControls);
            controlsRef.on("child_removed", updateControls);

            updateVoteUI();
        };

        /**
         *
         * @returns {*|jQuery}
         */
        var initVoteUI = function() {
            var dfd = $.Deferred(), subPromises = [];
            self.resultHandlers = [];
            var textDecorators = ["green", "bold", "shadowy"];
            $.each(self.resultsToRender, function(i, rType) {
                var reqDfd = $.Deferred();
                require(
                    [
                        "mod_livepoll/" + rType + "-result-lazy"
                    ], function(Handler) {
                        if (rType === "text") {
                            var currentTxtResult = new Handler(), txtPromises = [];

                            $.each(textDecorators, function(i, decoratorId) {
                                var txtDfd = $.Deferred();
                                txtPromises.push(txtDfd.promise());
                                require(
                                    [
                                        "mod_livepoll/" + decoratorId + "-text-result-lazy"
                                    ], function(TextDecorator) {
                                        currentTxtResult = new TextDecorator(currentTxtResult);
                                        txtDfd.resolve();
                                    }
                                );
                            });

                            $.when.apply($, txtPromises).done(function() {
                                self.resultHandlers.push(currentTxtResult);
                                reqDfd.resolve();
                            });
                        } else {
                            self.resultHandlers.push(new Handler());
                            reqDfd.resolve();
                        }
                    }
                );
                subPromises.push(reqDfd.promise());
            });

            $.when.apply($, subPromises).done(function() {
                dfd.resolve();
            });

            return dfd.promise();
        };

        /**
         * Initializes firebase library.
         */
        var initFirebase = function() {
            // Set the configuration for your app.
            var config = {
                apiKey: self.apiKey,
                authDomain: self.authDomain,
                databaseURL: self.databaseURL,
                projectId: self.projectID,
            };

            self.firebase.initializeApp(config);

            // Get a reference to the database service.
            self.database = self.firebase.database();
            self.auth = self.firebase.auth();
            self.auth.signInAnonymously().catch(function(error) {
                // Handle Errors here.
                var errorCode = error.code;
                var errorMessage = error.message;
                Log.error("Could not authenticate into firebase using anonymous setup.");
                Log.error(errorCode);
                Log.error(errorMessage);
            });
            self.auth.onAuthStateChanged(function(user) {
                if (user) {
                    Log.debug("User has signed in to firebase.");
                    self.fbuser = user;
                    initVoteUI().done(function() {
                        addDBListeners();
                        addControlListeners();
                    });
                } else {
                    Log.debug("User has signed out from firebase.");
                }
            });
        };

        /**
         * Module initialization function.
         *
         * @param apiKey
         * @param authDomain
         * @param databaseURL
         * @param projectID
         * @param pollKey
         * @param userKey
         * @param options
         * @param correctOption
         * @param resultsToRender
         */
        var init = function(apiKey, authDomain, databaseURL, projectID, pollKey, userKey, options, correctOption, resultsToRender) {
            self.apiKey = apiKey;
            self.authDomain = authDomain;
            self.databaseURL = databaseURL;
            self.projectID = projectID;
            self.options = options;
            self.correctOption = correctOption;
            self.pollKey = pollKey;
            self.userKey = userKey;
            self.resultsToRender = resultsToRender;
            self.closeVoting = false;
            self.higlightAnswer = false;
            self.listeningToClicks = false;

            resetVotes();

            $(document).ready(function() {
                /* global firebase */
                if (undefined === firebase) {
                    Log.error("Firebase not found. Live poll will not work.");
                    return;
                }
                self.firebase = firebase;
                initFirebase();
            });
        };

        return {
            "init": init
        };
    });
