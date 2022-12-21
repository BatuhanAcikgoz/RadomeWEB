if (!('Notification' in window))
	window.Notification = null;

if (loggedIn == 1) {


	if ($(".pms").length || $(".alerts").length) {
		$(document).ready(function () {
			if (Notification) {
				if (Notification.permission !== "granted")
					Notification.requestPermission();
			}

			window.setInterval(function () {
				$.getJSON(URLBuild('sorgu/pms'), function (data) {
					if (data.value > 0 && $('.pms').is(':empty')) {
						$(".pms").html(' <span class="badge badge-danger"><i class="fa fa-exclamation-circle custom-nav-exclaim"></i></span>');

						if (data.value !== 1) {
							var x_messages = newMessagesX;
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
									body: newMessage1, icon: siteIcon,
								});
							} else {
								var notification = new Notification(siteName, {
									body: x_messages.replace("{{count}}", data.value), icon: siteIcon,
								});
							}

							notification.onclick = function () {
								window.open(URLBuild('user/messaging', true));
							};

						}
					}
				});

				$.getJSON(URLBuild('sorgu/uyarilar'), function (data) {
					if (data.value > 0 && $('.alerts').is(':empty')) {
						$(".alerts").html(' <span class="badge badge-danger"><i class="fa fa-exclamation-circle custom-nav-exclaim"></i></span>');

						if (data.value !== 1) {
							var x_alerts = newAlertsX;
						}

						var alert_dropdown = $(".alert_dropdown");

						var new_alert_dropdown = '';

						for (i in data.alerts) {
							new_alert_dropdown += '<a class="dropdown-item" href="' + URLBuild('kullanici/uyarilar?view=' + data.alerts[i].id) + '">' + data.alerts[i].content_short + '</a>';
						}

						alert_dropdown.html(new_alert_dropdown);

						alert_dropdown.removeClass('dropdown-item');

						if (Notification.permission !== "granted")
							Notification.requestPermission();
						else {
							if (data.value == 1) {
								var notification = new Notification(siteName, {
									body: newAlert1, icon: siteIcon,
								});
							} else {
								var notification = new Notification(siteName, {
									body: x_alerts.replace("{{count}}", data.value), icon: siteIcon,
								});
							}

							notification.onclick = function () {
								window.open(URLBuild('kullanici/uyarilar', true));
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

}