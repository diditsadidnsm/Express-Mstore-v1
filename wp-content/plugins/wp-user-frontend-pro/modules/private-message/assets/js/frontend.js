(function ($) {
    if (!$('#wpuf-private-message').length) {
        return;
    }

    var RouterView = {
        template: '<router-view></router-view>'
    };

    var IndexPage = {
        template: '#tmpl-wpuf-private-message-index',

        data: function () {
            return {
                searching: false,
                search: '',
                messages: []
            }
        },

        created: function () {
            var vm = this;

            $.ajax({
                url: wpufPM.ajaxurl,
                method: 'get',
                type: 'json',
                data: {
                    action: 'wpuf_pm_route_data_index'
                }
            }).done(function (response) {
                if (response.data && response.data.messages) {
                    vm.messages = response.data.messages;
                }
            });
        },

        methods: {
            searchMessage: function () {
                var vm = this;

                vm.searching = true;

                $.ajax({
                    url: wpufPM.ajaxurl,
                    method: 'get',
                    type: 'json',
                    data: {
                        action: 'wpuf_pm_message_search',
                        content: this.search,
                    }
                }).done(function (response) {
                    if (response.data && response.data.messages) {
                        vm.messages = response.data.messages;
                    }

                    vm.searching = false;
                });
            },
            deleteMessage: function (id) {
                var vm = this;

                $.ajax({
                    url: wpufPM.ajaxurl,
                    method: 'get',
                    type: 'json',
                    data: {
                        action: 'wpuf_pm_delete_message',
                        id: id,
                    }
                }).done(function (response) {
                    if (response.data && response.data.messages) {
                        vm.messages = response.data.messages;
                    }
                });
            },
        }
    };

    // Vue.component('wpuf-pm-index-page', indexPage);

    var SinglePage = {
        template: '#tmpl-wpuf-private-message-single',

        data: function () {
            return {
                messages: []
            }
        },

        created: function () {
            var vm = this;
            $.ajax({
                url: wpufPM.ajaxurl,
                method: 'get',
                type: 'json',
                data: {
                    action: 'wpuf_pm_route_data_message',
                    userId: this.$route.params.id
                }
            }).done(function (response) {
                if (response.data && response.data.messages) {
                    vm.messages = response.data.messages;
                    vm.chat_with = response.data.chat_with;
                }
            });
        },

        computed: {
            userId: function () {
                return this.$route.params.id;
            }
        },

        methods: {
            sendMessage: function () {
                var vm = this;
                if (this.message) {
                    $.ajax({
                        url: wpufPM.ajaxurl,
                        method: 'get',
                        type: 'json',
                        data: {
                            action: 'wpuf_pm_message_send',
                            userId: this.$route.params.id,
                            message: this.message,
                        }
                    }).done(function (response) {
                        if (response.data && response.data.messages) {
                            vm.messages = response.data.messages;
                        }
                    });
                    this.message = '';
                }
            },
            deleteSingleMessage: function (id) {
                var vm = this;
                // console.log(id);
                $.ajax({
                    url: wpufPM.ajaxurl,
                    method: 'get',
                    type: 'json',
                    data: {
                        action: 'wpuf_pm_delete_single_message',
                        id: id,
                        userId: this.$route.params.id,
                    }
                }).done(function (response) {
                    if (response.data && response.data.messages) {
                        vm.messages = response.data.messages;
                    }
                });
            },
        }
    };

    // Vue.component('wpuf-pm-single-page', singlePage);

    var routes = [
        {
            path: '',
            name: 'wpufPMIndex',
            component: IndexPage
        },
        {
            path: '/user',
            component: RouterView,
            // redirect: {
            //     name: 'wpufPMUIndex'
            // },
            children: [
                {
                    path: ':id',
                    name: 'wpufPMSingle',
                    component: SinglePage
                }
            ]
        }
    ];

    var router = new VueRouter({
        routes: routes
    });

    new Vue({
        el: '#wpuf-private-message',
        router: router,

        watch: {
            '$route': function(to, from) {
                if ( 'wpufPMSingle' == to.name ) {
                    wpufpopup.closeModal();
                }
            }
        }
    });
})(jQuery)
