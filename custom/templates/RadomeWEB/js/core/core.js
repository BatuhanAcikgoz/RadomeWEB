function URLBuild(path, full = false) {
    return (full ? fullSiteURL : siteURL) + path;
}

$(document).ready(function () {
    $('[data-action="logout"]').click(function () {
        const url = $(this).data('link');
        $.post(url, {
            token: csrfToken
        }).done(function () {
            window.location.reload();
        });
    });
});

$('.navbar-toggler').click(function() {
    $('.coldfire-navbar-menu').addClass("active");
    $('.overlay').addClass("active");
});
$('#nav-header-close').click(function() {
    $('.coldfire-navbar-menu').removeClass("active");
    $('.overlay').removeClass("active");
});

if ((particles == "yes") && ($("#header-pjs").length)) {
    $(window).on("load", function() {
        particlesJS.load("header-pjs", pjsPath);
    });
}

if (loadingTime) {
    $('#page_load_tooltip').attr('title', loadingTime).tooltip();
}

$(document).ready(function() {
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $("#scroll").stop().fadeIn();
        } else {
            $("#scroll").stop().fadeOut();
        }
    });
    $("#scroll").click(function() {
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
});

$.fn.tooltip.Constructor.Default.whiteList["span"].push("style");
$.fn.tooltip.Constructor.Default.whiteList["a"].push("style");

$(".pop").popover({ trigger: "manual", html: "true", placement: "top" })
    .on("mouseenter", function() {
        var _this = this;
        $(this).popover("show");
        $(".popover").on("mouseleave", function() {
            $(_this).popover('hide');
        });
    }).on("mouseleave", function() {
        var _this = this;
        setTimeout(function() {
            if (!$(".popover:hover").length) {
                $(_this).popover('hide');
            }
        }, 300);
    });

$('.more-dropdown').hover(
    function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(25).fadeIn();
    },
    function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(25).fadeOut();
    }
);
$('.more-dropdown-menu').hover(
    function() {
        $(this).stop(true, true);
    },
    function() {
        $(this).stop(true, true).delay(25).fadeOut();
    }
);

$(function() {
    $('[data-toggle="tooltip"]').tooltip()
});
$(function() {
    $('[rel="tooltip"]').tooltip()
});

$('[data-toggle="popover"]').popover({ trigger: "manual", html: true, animation: false }).on("mouseenter", function() {
    var _this = this;
    $(this).popover("show");
    $(".popover").on("mouseleave", function() {
        $(_this).popover('hide');
    });
}).on("mouseleave", function() {
    var _this = this;
    setTimeout(function() {
        if (!$(".popover:hover").length) {
            $(_this).popover("hide");
        }
    }, 300);
});

$(function () {
    const cachedUsers = {};

    $('*[data-poload]').mouseenter(function () {
        const elem = this;
        $.get($(elem).data('poload'),
            function (d) {
                (debugging ? console.log(d) : '');
                const data = JSON.parse(d);
                cachedUsers[$(elem).data('poload')] = data;
                const tmp = document.createElement('div');
                tmp.innerHTML = data.html;
                const img = tmp.getElementsByTagName('img')[0];
                const image = new Image();
                image.src = img.src;
            }
        );
    });

    $('*[data-poload]').popup({
        hoverable: true,
        html: '<i class="circle notch loading icon"></i>',
        delay: { show: 500, hide: 200 },
        onShow: function (e) { this.html(cachedUsers[$(e).data('poload')].html) }
    });

    const timezone = document.getElementById('timezone');

    if (timezone) {
        const timezoneValue = Intl.DateTimeFormat().resolvedOptions().timeZone;
        if (timezoneValue) {
            timezone.value = timezoneValue;
        }
    }

});

function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();

    Swal.fire({
        title: "IP " + copied, 
        text: swal_server_copy,
        icon: "success",
        confirmButtonText: close
    });
}

const announcements = document.querySelectorAll('[id^="announcement"]');
announcements.forEach((announcement) => {
    const closeButton = announcement.querySelector('.close');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            document.cookie = announcement.id + '=true; path=/';
        });
    }
});