/**
 * tabs
 * @author  lzf
 */
function tabs(tabTitle, tab_content) {
	var index = $(tabTitle).children(".hover").index()
	$(tab_content).children().eq(index).show().siblings().hide();
	$(tabTitle).children().click(function () {
		var index = $(this).index();
		$(this).addClass("hover").siblings().removeClass("hover");
		$(tab_content).children().eq(index).show().siblings().hide();
		return false;
	});
}


$(function () {

	var w = $(window).width();
	var h = $(window).height();
	$(".head_search").click(function () {
		$(".search_box").stop().slideToggle();
		$(".mask").fadeIn();
	})

	$(".mask").click(function () {
		$(this).fadeOut();
		$(".search_box").fadeOut();
	})
	$(".search_box_close").click(function () {
		$(".search_box,.mask").fadeOut();
	})
	$(".head_lag_t").click(function () {
		$(".head_lag_down").slideToggle();
	})




	//移动端
	if (w < 1025) {

		$(".mobile_menu_box").height(h);

		$(".mobile_menu_dl dd").each(function () {
			var ss = $(this).find(".mobile_menu_down a").length;
			if (ss > 0) {
				$(this).find(".mobile_menu_tt_icon").show();
			} else {
				$(this).find(".mobile_menu_tt_icon").hide();
			}
		})

		//手机站
		$(".mobile_menu_btn").click(function () {
			$(".mobile_menu_box").addClass("show");
			$(".mask").fadeOut();
			$(".mobile_search_box,.mobile_lag_box").fadeOut();
			$(".mobile_menu_mask").fadeIn();
		})

		$(".mobile_search_icon").click(function () {
			if ($(".mobile_search_box").is(":hidden")) {
				$(".mobile_search_box").slideDown();
				$(".mobile_lag_box").fadeOut();
				$(".mask").fadeIn();
			} else {
				$(".mobile_search_box").fadeOut();
				$(".mask").fadeOut();
			}

		})

		$(".mobile_lag_icon").click(function () {
			if ($(".mobile_lag_box").is(":hidden")) {
				$(".mobile_lag_box").slideDown();
				$(".mobile_search_box").fadeOut();
				$(".mask").fadeIn();
			} else {
				$(".mobile_lag_box").fadeOut();
				$(".mask").fadeOut();
			}
		})

		$(".mobile_menu_tt_icon").click(function () {
			$(this).toggleClass("hover");
			$(this).parents("dd").find(".mobile_menu_down").toggle();
		})

		$(".mask").click(function () {
			$(this).fadeOut();
			$(".mobile_search_box,.mobile_lag_box,.search_box").fadeOut();
		})

		$(".mobile_menu_mask").click(function () {
			$(this).fadeOut();
			$(".mobile_menu_box").removeClass("show");
		})
	} else {

	}

	function tabsa(tabTitle, tab_content) {
		var index = $(tabTitle).children(".hover").index()
		$(tab_content).children().eq(index).show().siblings().hide();
		$(tabTitle).children().click(function () {
			var index = $(this).index();
			$(this).addClass("hover").siblings().removeClass("hover");
			$(tab_content).children().eq(index).show().siblings().hide();
			return false;
		});
	}
	tabsa('.layui_tab_title', '.layui-tab-item')

	/*
	 * banner
	 */
	var banner_num = $(".banner .item").length;
	if (banner_num > 1) {
		var banner = $('.banner');
		banner.owlCarousel({
			loop: false,
			dots: true,
			autoplay: true,
			paginationSpeed: 600,
			items: 1
		})
	} else {
		$(".banner").css({ "display": "block" });
	}






	//    about_quality
	var ind_solu_scroll4 = $(".about_qu");
	ind_solu_scroll4.owlCarousel({
		loop: false,
		dots: true,
		autoplay: true,
		autoplayTimeout: 5000,
		responsive: {
			1400: {
				items: 2,
				margin: 26,
			},
			1200: {
				items: 2,
				margin: 18,
			},
			1024: {
				items: 2,
				margin: 18,
			},
			768: {
				items: 2,
				margin: 15,
			},
			300: {
				items: 1,
				margin: 10,
			}
		}
	})
	$('.about_left').click(function () {
		ind_solu_scroll4.trigger('prev.owl.carousel', [600]);
	})
	$('.about_right').click(function () {
		ind_solu_scroll4.trigger('next.owl.carousel', [600]);
	})

	// about_company
	var ind_solu_scroll6 = $(".about_zhengshu_ul");
	ind_solu_scroll6.owlCarousel({
		loop: false,
		dots: true,
		autoplay: true,
		autoplayTimeout: 5000,
		responsive: {
			1400: {
				items: 5,
				margin: 26,
			},
			1200: {
				items: 4,
				margin: 18,
			},
			1024: {
				items: 4,
				margin: 18,
			},
			768: {
				items: 3,
				margin: 15,
			},
			300: {
				items: 1,
				margin: 10,
			}
		}
	})



	var ind_solu_scroll1 = $(".ind_solu_scroll");
	ind_solu_scroll1.owlCarousel({
		loop: false,
		dots: true,
		autoplay: true,
		autoplayTimeout: 5000,
		responsive: {
			1400: {
				items: 4,
				margin: 26,
			},
			1200: {
				items: 4,
				margin: 18,
			},
			1024: {
				items: 3,
				margin: 18,
			},
			768: {
				items: 3,
				margin: 15,
			},
			300: {
				items: 2,
				margin: 10,
			}
		}
	})
	$('.commodity-con_item_prev').click(function () {
		ind_solu_scroll1.trigger('prev.owl.carousel', [600]);
	})
	$('.commodity-con_item_next').click(function () {
		ind_solu_scroll1.trigger('next.owl.carousel', [600]);
	})



	// About_company
	var ind_solu_scroll5 = $(".about_tab");
	ind_solu_scroll5.owlCarousel({
		loop: false,
		dots: true,
		autoplay: true,
		autoplayTimeout: 5000,
		responsive: {
			1400: {
				items: 3,
				margin: 26,
			},
			1200: {
				items: 3,
				margin: 18,
			},
			1024: {
				items: 3,
				margin: 18,
			},
			768: {
				items: 2,
				margin: 15,
			},
			300: {
				items: 1,
				margin: 10,
			}
		}
	})

	$('.about-con-left').click(function () {
		ind_solu_scroll5.trigger('prev.owl.carousel', [600]);
	})
	$('.about-con-right').click(function () {
		ind_solu_scroll5.trigger('next.owl.carousel', [600]);
	})





	var ind_solu_scroll2 = $(".about_quality_banner");
	ind_solu_scroll2.owlCarousel({
		loop: false,
		dots: false,
		responsive: {
			1400: {
				items: 1,
				margin: 26,
			},
			1200: {
				items: 1,
				margin: 18,
			},
			1024: {
				items: 1,
				margin: 18,
			},
			768: {
				items: 1,
				margin: 15,
			},
			300: {
				items: 1,
				margin: 10,
			}
		}
	})

	$('.about_quality_banner_next').click(function () {
		ind_solu_scroll2.trigger('next.owl.carousel', [600]);
	})


	// quelity_inspation
	// owl图片切换的回调函数
	var quelity_zong = ($(".about_quality_banner .owl-wrapper-outer .owl-wrapper").children(".owl-item").length - $(".about_quality_banner .owl-wrapper-outer .owl-wrapper").children(".cloned").length);
	// console.log(quelity_zong)
	var quelity_index = (($(".about_quality_banner .owl-wrapper-outer .owl-wrapper").children(".active").index()) - 1);
	console.log(quelity_index)
	$(".que_dangqian").html("0" + quelity_index);
	$(".que_zongde").html("0" + quelity_zong);

	ind_solu_scroll2.on('changed.owl.carousel', function (event) {
		var quelity_zong = ($(".about_quality_banner .owl-wrapper-outer .owl-wrapper").children(".owl-item").length - $(".about_quality_banner .owl-wrapper-outer .owl-wrapper").children(".cloned").length);
		// console.log(quelity_zong)
		var quelity_index = ($(".about_quality_banner .owl-wrapper-outer .owl-wrapper").children(".active").index());
		if (quelity_index > quelity_zong) {
			quelity_index = 1
		}
		console.log(quelity_index)
		$(".que_dangqian").html("0" + quelity_index);
		$(".que_zongde").html("0" + quelity_zong);
	})









	function tabs(tabTitle, tab_content) {
		var index = $(tabTitle).children(".hover").index()
		$(tab_content).children().eq(index).show().siblings().hide();
		$(tabTitle).children().click(function () {
			var index = $(this).index();
			$(this).addClass("hover").siblings().removeClass("hover");
			$(tab_content).children().eq(index).show().siblings().hide();
			return false;
		});
	}
	tabs('.include_tab', '.include_con')



	function tabss(tabTitle, tab_content) {
		var index = $(tabTitle).children(".hover").index()
		$(tab_content).children().eq(index).show().siblings().hide();
		$(tabTitle).children().click(function () {
			var index = $(this).index();
			$(this).addClass("hover").siblings().removeClass("hover");
			$(tab_content).children().eq(index).show().siblings().hide();
			return false;
		});
	}
	tabss('.include_tab1', '.include_con1')




	// 返回顶部按钮的js   
	function back_top(back_top_btn, number) {
		// 先隐藏返回顶部按钮
		$(back_top_btn).hide();

		// 监听浏览器的滚动事件
		$(window).on("scroll", function () {
			if ($(window).scrollTop() >= number) {
				$(back_top_btn).stop().show();

				$(back_top_btn).on("click", function () {
					$("html, body").stop().animate({
						"scrollTop": "0"
					})
				})

			} else {
				$(back_top_btn).stop().hide();
			}
		})
	}
	back_top('.index_help_ul .index_help_ding', 1500)



	function maodian() {
		var limao = $("#products-introduce-ul>li").click(function () {
			for (a = 0; a < limao.length; a++) {
				limao[a].addClass("products-introduce-list")
			}
		})
	}
	maodian();

	function hover2() {
		$(".index_help_youxi").hover(function () {
			$(".index_help_jianjie2").css("display", "flex");
		}, function () {
			$(".index_help_jianjie2").css("display", "none")
		})

	}
	hover2()

	function hover3() {
		$(".index_help_dianhau").hover(function () {
			$(".index_help_jianjie3").css("display", "flex");
		}, function () {
			$(".index_help_jianjie3").css("display", "none")
		})

	}
	hover3()


	function hover4() {
		$(".index_help_xiaoniao").hover(function () {
			$(".index_help_jianjie4").css("display", "flex");
		}, function () {
			$(".index_help_jianjie4").css("display", "none")
		})

	}
	hover4()
	function hover5() {
		$(".index_help_das").hover(function () {
			$(".index_help_jianjie5").css("display", "flex");
		}, function () {
			$(".index_help_jianjie5").css("display", "none")
		})

	}
	hover5()



	function last_a() {
		$(".application-nav a").last().css(
			"color", "#0074c5"
		);
	}
	last_a()

})