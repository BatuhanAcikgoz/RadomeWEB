if (page !== '') {

    if (page === 'login' || page === 'register' || page === 'complete_signup') {

        $(function() {
            $('.button-checkbox').each(function() {
                var $widget = $(this),
                    $button = $widget.find('button'),
                    $checkbox = $widget.find('input:checkbox'),
                    color = $button.data('color'),
                    settings = {
                        on: {
                            icon: 'fas fa-check-square'
                        },
                        off: {
                            icon: 'far fa-square'
                        }
                    };
                $button.on('click', function() {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    $checkbox.triggerHandler('change');
                    updateDisplay();
                });
                $checkbox.on('change', function() {
                    updateDisplay();
                });

                function updateDisplay() {
                    var isChecked = $checkbox.is(':checked');
                    $button.data('state', (isChecked) ? "on" : "off");
                    $button.find('.state-icon')
                        .removeClass()
                        .addClass('state-icon ' + settings[$button.data('state')].icon);
                    if (isChecked) {
                        $button
                            .removeClass('btn-secondary')
                            .addClass('btn-' + color + ' active');
                    } else {
                        $button
                            .removeClass('btn-' + color + ' active')
                            .addClass('btn-secondary');
                    }
                }

                function init() {
                    updateDisplay();
                    if ($button.find('.state-icon').length == 0) {
                        $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i>');
                    }
                }
                init();
            });
        });

    } else if (page === 'cc_messaging') {

        $('#InputTo').tokenfield({
            autocomplete: {
                source: allUsers,
                delay: 100,
                minLength: 3
            },
            showAutocompleteOnFocus: true
        });

    } else if (page === 'profile') {

        $('#imageModal').on('show.bs.modal', function(e) {
            $("select").imagepicker();
        });

        if (loggedIn == 1) {

            function deletePost(post) {
                if (confirm(confirmDelete)) {
                    document.getElementById("delete" + post).submit();
                }
            }

            function deleteReply(post) {
                if (confirm(confirmDelete)) {
                    document.getElementById("deleteReply" + post).submit();
                }
            }

        }

        $(function() {
            var postId = window.location.hash.replace('#post-', '');
            var postElem = '#post-id-' + postId;
            setTimeout(function() {
                $('html, body').animate({ scrollTop: $(postElem).offset().top - 15 }, 800);
            }, 100);
        });

    } else if (page === 'status') {
        $(function () {
            $(".server").each(function () {
                let serverID = $(this).data("id");
                let serverBungee = $(this).data("bungee");
                let serverBedrock = $(this).data("bedrock");
                let serverPlayerList = $(this).data("players");
                let serverElem = '#server' + serverID + '[data-id=' + serverID + ']';

                const paramChar = URLBuild('').includes('?') ? '&' : '?';

                $.getJSON(URLBuild('queries/server/' + paramChar + 'id=' + serverID), function (data) {
                    var content = '';
                    var players = '';
                    if (data.status_value === 1) {
                        $(serverElem).addClass("green");
                        content = data.player_count + "/" + data.player_count_max;
                        if (serverBungee === 1) {
                            players = bungeeInstance;
                        } else if (serverBedrock === 1) {
                            players = '';
                        } else {
                            if (serverPlayerList === 1) {
                                if (data.player_count > 0 && data.player_list.length <= 0) {
                                    // Weird edge case where player list is empty but the player count is > 0
                                    if (data.player_count > 1) {
                                        players += xPlayersOnline.replace('{{count}}', data.player_count);
                                    } else {
                                        players += onePlayerOnline;
                                    }
                                } else if (data.player_list.length > 0) {
                                    for (var i = 0; i < data.player_list.length; i++) {
                                        players += '<a href="' + URLBuild('profile/' + data.player_list[i].name) + '" data-tooltip="' + data.player_list[i].name + '" data-variation="mini" data-inverted="" data-position="bottom center"><img class="ui mini circular image" src="' + avatarSource.replace('{identifier}', data.player_list[i].id).replace('{size}', 64) + '" alt="' + data.player_list[i].name + '"></a>';
                                    }

                                    if (data.player_list.length < data.player_count) {
                                        players += '<span class="ui blue circular label">+' + (data.player_count - data.player_list.length) + '</span>';
                                    }

                                } else {
                                    players += noPlayersOnline;
                                }
                            }
                        }
                    } else {
                        $(serverElem).addClass("red");
                        content = offline;
                        players = noPlayersOnline;
                    }

                    $(serverElem).find('#server-status').html(content);
                    $(serverElem).find('#server-players').html(players);
                });
            });
        });

    } else if (route.indexOf("/forum/topic/") != -1) {

        $(function() {
            var postId = window.location.hash.replace('#post-', '');
            var postElem = '#post-' + postId;
            setTimeout(function() {
                $('html, body').animate({ scrollTop: $(postElem).offset().top - 15 }, 800);
            }, 100);
        });

    } else if (route.indexOf("/resources/new") != -1) {

        $(document).ready(function() {
            $('#priceFormGroup').hide();
        });
        $('#inputType').change(function() {
            if ($('#inputType').val() === "premium") {
                $('#priceFormGroup').show();
            } else {
                $('#priceFormGroup').hide();
            }
        });

    }

}