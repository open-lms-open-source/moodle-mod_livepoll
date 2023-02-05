
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
 * Live poll Option controller  module.
 *
 * @package mod_livepoll
 * @copyright Copyright (c) 2018 Open LMS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* jshint esversion: 6 */

class OptionItem {
    constructor(option) {
        this.optionId = option.optionid;
        this.value = option.value;
        this.title = option.title;
        this.voteCnt = 0; // votes for this options
        this.isHighest = false; // style binding
        this.isOwnVote = false; // style binding
        this.highlight = false;
    }
}

class OptionCtrl {
    constructor(options, correctOption) {
        this.optionList = new Map();
        this.correctOption = correctOption;
        this.votingClosed = false;
        this.highlightVote = false;
        this.chartCtrl = null;
        for (const key in options) {
            this.addOptionItem(key, options[key]);
        }
    }

    addOptionItem(key, option){
        this.optionList.set(key, new OptionItem(option));
        this.optionList= new Map(this.optionList); // new Map to trigger ngFor
    }

    doHiglightAnswer(state){
        this.highlightVote = state;
        this.optionList.forEach((item, key) => {
            item.highlight = state && key === this.correctOption;
        });
    }

    updateVotes(votes, userKey){
        this.clearAllVotes();
        votes.forEach((vote) => {
            const optionItem = this.optionList.get(vote.val().option);
            optionItem.voteCnt++;
            if(vote.key === userKey){
                this.updateOwnVoteInfo(optionItem);
            }
        });
        this.updateHighestVotedOptions();
        if(this.chartCtrl){
            this.chartCtrl.performUpdate(this.optionList);
        }
    }
    
    updateOwnVoteInfo(optionItem){
        for (let [key, item] of this.optionList.entries()) {
            item.isOwnVote = false;
        }
        optionItem.isOwnVote = true;
    }

    clearAllVotes(){
        this.optionList.forEach((item) => {
            item.voteCnt = 0;
        });
    }

    updateHighestVotedOptions() {

        var highestValue = -Infinity;
        var higestOptions = [];

        for (let [key, item] of this.optionList.entries()) {
            item.isHighest = false;
            if(item.voteCnt > 0 ){
                if (item.voteCnt > highestValue) {
                    highestValue = item.voteCnt;
                    higestOptions = [];  // higher vote count found, clear highest options list
                    higestOptions.push(item);
                }else if(item.voteCnt === highestValue){
                    higestOptions.push(item);
                }
            }
        }

        higestOptions.forEach((item) => {
                item.isHighest = true;
            });
    }
}