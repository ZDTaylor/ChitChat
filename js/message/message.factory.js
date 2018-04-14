(function () {
    'use strict';

    angular
        .module('app.messages')
        .factory('Message', Message);

    function Message() {
        function MessageConstructor(args = {}) {
            var message = this;
            message = {
                id: args.id || null,
                poster: args.poster || null,
                content: args.content || "",
                mentions: args.mentions || [],
                likes: args.likes || 0,
                dislikes: args.dislikes || 0,
                liked: args.liked || false,
                disliked: args.disliked || false,
                mentionUser: mentionUser
            };

            return message;
        }

        return MessageConstructor;

        ////////////////
        function mentionUser(id) {
            if (this.mentions.indexOf(id) === -1) {
                this.mentions.push(id);
            }
        }
    }
})();