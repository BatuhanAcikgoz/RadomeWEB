if (!('Notification' in window))
	window.Notification = null;

if (loggedIn == 1) {


	if ($(".pms").length || $(".alerts").length) {
		$(document).ready(function () {
			if (Notification) {
				if (Notification.permission !== "granted")
					Notification.requestPermission();
			}

			toastr.options.closeButton = true;
			toastr.options.positionClass = 'toast-bottom-left';

			$.getJSON(URLBuild('queries/pms'), function (data) {
				var pm_dropdown = $(".pm_dropdown");

				if (data.value > 0) {
					$(".pms").html(' <span class="badge badge-danger"><i class="fa fa-exclamation-circle custom-nav-exclaim"></i></span>');
					if (pm_dropdown.html() == loading) {

						var new_pm_dropdown = '';
						for (i in data.pms) {
							new_pm_dropdown += '<a class="dropdown-item" href="' + URLBuild('user/messaging?action=view&amp;message=' + data.pms[i].id) + '">' + data.pms[i].title + '</a>';
						}
						pm_dropdown.html(new_pm_dropdown);
						pm_dropdown.removeClass('dropdown-item');
					}

				} else {
					pm_dropdown.html('<span>' + noMessages + '</span>');
					pm_dropdown.addClass('noclick');
				}
			});
			$.getJSON(URLBuild('queries/alerts'), function (data) {
				var alert_dropdown = $(".alert_dropdown");

				if (data.value > 0) {
					$(".alerts").html(' <span class="badge badge-danger"><i class="fa fa-exclamation-circle custom-nav-exclaim"></i></span>');

					if (alert_dropdown.html() == loading) {

						var new_alert_dropdown = '';

						for (i in data.alerts) {
							new_alert_dropdown += '<a class="dropdown-item" href="' + URLBuild('user/alerts?view=' + data.alerts[i].id) + '">' + data.alerts[i].content_short + '</a>';
						}

						alert_dropdown.html(new_alert_dropdown);
						alert_dropdown.removeClass('dropdown-item');
					}

				} else {
					alert_dropdown.html('<span>' + noAlerts + '</span>');
					alert_dropdown.addClass('noclick');
				}
			});

			window.setInterval(function () {
				$.getJSON(URLBuild('queries/pms'), function (data) {
					if (data.value > 0 && $('.pms').is(':empty')) {
						$(".pms").html(' <span class="badge badge-danger"><i class="fa fa-exclamation-circle custom-nav-exclaim"></i></span>');

						if (data.value == 1) {
							toastr.info(newMessage1);
						} else {
							var x_messages = newMessagesX;
							toastr.info(x_messages.replace("{x}", data.value));
						}

						var pm_dropdown = $(".pm_dropdown");

						var new_pm_dropdown = '';

						for (i in data.pms) {
							new_pm_dropdown += '<a class="dropdown-item alert-msg-list" href="' + URLBuild('user/messaging?action=view&amp;message=' + data.pms[i].id) + '">' + data.pms[i].title + '</a>';
						}

						pm_dropdown.html(new_pm_dropdown);

						pm_dropdown.removeClass('dropdown-item');

						if (Notification.permission !== "granted")
							Notification.requestPermission();
						else {
							if (data.value == 1) {
								var notification = new Notification(siteName, {
									body: newMessage1,
								});
							} else {
								var notification = new Notification(siteName, {
									body: x_messages.replace("{x}", data.value),
								});
							}

							notification.onclick = function () {
								window.open(URLBuild('user/messaging', true));
							};

						}
					}
				});

				$.getJSON(URLBuild('queries/alerts'), function (data) {
					if (data.value > 0 && $('.alerts').is(':empty')) {
						$(".alerts").html(' <span class="badge badge-danger"><i class="fa fa-exclamation-circle custom-nav-exclaim"></i></span>');

						if (data.value == 1) {
							toastr.info(newAlert1);
						} else {
							var x_alerts = newAlertsX;
							toastr.info(x_alerts.replace("{x}", data.value));
						}

						var alert_dropdown = $(".alert_dropdown");

						var new_alert_dropdown = '';

						for (i in data.alerts) {
							new_alert_dropdown += '<a class="dropdown-item" href="' + URLBuild('user/alerts?view=' + data.alerts[i].id) + '">' + data.alerts[i].content_short + '</a>';
						}

						alert_dropdown.html(new_alert_dropdown);

						alert_dropdown.removeClass('dropdown-item');

						if (Notification.permission !== "granted")
							Notification.requestPermission();
						else {
							if (data.value == 1) {
								var notification = new Notification(siteName, {
									body: newAlert1,
								});
							} else {
								var notification = new Notification(siteName, {
									body: x_alerts.replace("{x}", data.value),
								});
							}

							notification.onclick = function () {
								window.open(URLBuild('user/alerts', true));
							};

						}
					}
				});
			}, 20000);
		});

		$('.alert-dropdown, .user-dropdown, .pm-dropdown').hover(
			function () {
				$(this).find('.dropdown-menu').stop(true, true).delay(25).fadeIn();
			},
			function () {
				$(this).find('.dropdown-menu').stop(true, true).delay(25).fadeOut();
			}
		);

		$('.alert-dropdown-menu, .user-dropdown-menu, .pm-dropdown-menu').hover(
			function () {
				$(this).stop(true, true);
			},
			function () {
				$(this).stop(true, true).delay(25).fadeOut();
			}
		);

	}

	if ($('div.show-punishment').length) {
		$('.show-punishment').modal('show');
	}

} else if (cookie == 1) {

	toastr.options.timeOut = 0;
	toastr.options.extendedTimeOut = 0;
	toastr.options.closeButton = true;
	toastr.options.onclick = function () {
		$('.toast .toast-close-button').focus();
	}
	toastr.options.onHidden = function () {
		$.cookie('accept', 'accepted', {
			path: '/'
		});
	}
	toastr.options.positionClass = 'toast-bottom-left';
	toastr.info(cookieNotice);
}