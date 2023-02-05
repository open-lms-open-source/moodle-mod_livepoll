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

/* jshint esversion: 6 */

class AddonModLivePoll {

    constructor() {
        this.optionCtrl = null;
    }

    init(poolConfig){
        this.intro = poolConfig.intro;
        this.apiKey = poolConfig.apiKey;
        this.authDomain = poolConfig.authDomain;
        this.databaseURL = poolConfig.databaseURL;
        this.projectID = poolConfig.projectID;
        this.correctOption = poolConfig.correctOption;
        this.options = poolConfig.options;
        this.optionCtrl = new OptionCtrl(this.options, this.correctOption);
        this.pollKey = poolConfig.pollKey;
        this.userKey = poolConfig.userKey;
        this.resultsToRender = poolConfig.resultsToRender;
        this.votingClosed = false;
        if(this.firebase) {
            this.firebase.delete();
        }

        // wait for view ready
        this.elementReady('#livepoll').then(()=>{
            this.initFirebase();
        });
    }

    /**
     * Cleanup ressource on page close
     */
    doCleanup(){
        this.firebase.delete();
        this.firebase = null;
    }

    /**
     * Initializes firebase library.
     */
    initFirebase() {
        // Set the configuration for your app.
        var config = {
            apiKey: this.apiKey,
            authDomain: this.authDomain,
            databaseURL: this.databaseURL,
            projectId: this.projectID,
        };
        this.firebase = window.firebase.initializeApp(config);

        // Get a reference to the database service.
        this.database = this.firebase.database();
        this.auth = this.firebase.auth();

        this.auth.signInAnonymously().catch(function(error) {
            // Handle Errors here.
            var errorCode = error.code;
            var errorMessage = error.message;
            /*Log.error("Could not authenticate into firebase using anonymous setup.");
            Log.error(errorCode);
            Log.error(errorMessage);*/
        });
        this.auth.onAuthStateChanged((user) => {
            if (user) {
                //Log.debug("User has signed in to firebase.");
                this.fbuser = user;
                this.initVoteUI();
                this.addDBListeners();

            } else {
                //Log.debug("User has signed out from firebase.");
            }
        });
    }

    initVoteUI(){
        this.resultsToRender.forEach( (resultType) => {
            if (resultType !== "text") {
                this.optionCtrl.chartCtrl = new ChartCtrl(resultType);
            }
        });
    }

    /**
     * Adds listeners for state changes in the poll.
     */
    addDBListeners() {
        var votesRef = this.database.ref("polls/" + this.pollKey + "/votes");
        votesRef.on("child_added", () => {
            this.updateVoteCount();
        });
        votesRef.on("child_changed", () => {
            this.updateVoteCount();
        });
        votesRef.on("child_removed", () => {this.updateVoteCount();});

        var controlsRef = this.database.ref("polls/" + this.pollKey + "/controls");
        controlsRef.on("child_added", () => {this.updateControls();});
        controlsRef.on("child_changed", () => {this.updateControls();});
        controlsRef.on("child_removed", () => {this.updateControls();});
    }

    /**
     * Updates the vote count and vote UI for a poll snapshot.
     */
    updateVoteCount(){
        var votesRef = this.database.ref("polls/" + this.pollKey + "/votes");
        votesRef.once("value").then((votes) => {
            this.optionCtrl.updateVotes(votes, this.userKey);
        });
    }

    updateControls(){
        var controlsRef = this.database.ref("polls/" + this.pollKey + "/controls");

        controlsRef.once("value").then((controlsSnapshot) => {
            var controlStatus = controlsSnapshot.val();

            this.optionCtrl.votingClosed = !!controlStatus.closeVoting;
            this.optionCtrl.doHiglightAnswer(!!controlStatus.higlightAnswer);

        });
    }

    doVote(optionItem){
        if(this.optionCtrl.votingClosed){
            return;
        }

        var vote = {
            option: optionItem.optionId
        };
        var voteRef = this.database.ref("polls/" + this.pollKey + "/votes/" + this.userKey);
        voteRef.once("value").then(function (voteSnapshot) {
            if (voteSnapshot.val() && voteSnapshot.val().option === optionItem.option) {
                voteRef.remove();
            } else {
                voteRef.set(vote);
            }
        });
        this.optionCtrl.updateOwnVoteInfo(optionItem);
    }

    toggleCloseVoting(event){
        var controlRef = this.database.ref("polls/" + this.pollKey + "/controls/closeVoting");
        controlRef.set((event)?1:0);

    }

    toggleHighlight(event){
        var controlRef = this.database.ref("polls/" + this.pollKey + "/controls/higlightAnswer");
        controlRef.set((event)?1:0);

    }

    elementReady(selector) {
        return new Promise((resolve) => {
            let el = document.querySelector(selector);
            if (el) {
                resolve(el);
                return;
            }
            new MutationObserver((mutationRecords, observer) => {
                // Query for elements matching the specified selector
                Array.from(document.querySelectorAll(selector)).forEach((element) => {
                    resolve(element);
                    //Once we have resolved we don't need the observer anymore.
                    observer.disconnect();
                });
            }).observe(document.documentElement, {
                childList: true,
                subtree: true
            });
        });
    }
}

const result = {
    AddonModLivePoll: new AddonModLivePoll(),
};

result;