(function () {
    'use strict';

    angular
        .module('app.messages')
        .factory('Message', Message);

    function Message() {
        function MessageConstructor(args) {
            if (typeof (args) == 'undefined') {
                args = {};
            }
            var message = this;
            message = {
                messageID: args.messageID || null,
                poster: args.poster || null,
                content: args.content || "",
                mentions: args.mentions || [],
                net_likes: args.net_likes || 0,
                reaction: args.reaction || 0
            };

            return message;
        }

        return MessageConstructor;
    }
})();