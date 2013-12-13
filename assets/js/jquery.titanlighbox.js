;(function($) {
	$.fn.wipetouch = function(settings) {
		var config = {
			moveX : 40,
			moveY : 40,
			tapToClick : false,
			preventDefault : true,
			allowDiagonal : false,
			wipeLeft : false,
			wipeRight : false,
			wipeUp : false,
			wipeDown : false,
			wipeUpLeft : false,
			wipeDownLeft : false,
			wipeUpRight : false,
			wipeDownRight : false,
			wipeMove : false,
			wipeTopLeft : false,
			wipeBottomLeft : false,
			wipeTopRight : false,
			wipeBottomRight : false
		};
		if(settings) {
			$.extend(config, settings)
		}
		this.each(function() {
			var startX;
			var startY;
			var startDate = false;
			var curX;
			var curY;
			var isMoving = false;
			var touchedElement = false;
			var useMouseEvents = false;
			var clickEvent = false;
			function onTouchStart(e) {
				var start = useMouseEvents || (e.originalEvent.touches && e.originalEvent.touches.length > 0);
				if(!isMoving && start) {
					if(config.preventDefault) {
						e.preventDefault()
					}
					if(config.allowDiagonal) {
						if(!config.wipeDownLeft) {
							config.wipeDownLeft = config.wipeBottomLeft
						}
						if(!config.wipeDownRight) {
							config.wipeDownRight = config.wipeBottomRight
						}
						if(!config.wipeUpLeft) {
							config.wipeUpLeft = config.wipeTopLeft
						}
						if(!config.wipeUpRight) {
							config.wipeUpRight = config.wipeTopRight
						}
					}
					if(useMouseEvents) {
						startX = e.pageX;
						startY = e.pageY;
						$(this).bind("mousemove", onTouchMove);
						$(this).one("mouseup", onTouchEnd)
					} else {
						startX = e.originalEvent.touches[0].pageX;
						startY = e.originalEvent.touches[0].pageY;
						$(this).bind("touchmove", onTouchMove)
					}
					startDate = new Date().getTime();
					curX = startX;
					curY = startY;
					isMoving = true;
					touchedElement = $(e.target)
				}
			}

			function onTouchEnd(e) {
				if(config.preventDefault) {
					e.preventDefault()
				}
				if(useMouseEvents) {
					$(this).unbind("mousemove", onTouchMove)
				} else {
					$(this).unbind("touchmove", onTouchMove)
				}
				if(isMoving) {
					touchCalculate(e)
				} else {
					resetTouch()
				}
			}

			function onTouchMove(e) {
				if(config.preventDefault) {
					e.preventDefault()
				}
				if(useMouseEvents && !isMoving) {
					onTouchStart(e)
				}
				if(isMoving) {
					if(useMouseEvents) {
						curX = e.pageX;
						curY = e.pageY
					} else {
						curX = e.originalEvent.touches[0].pageX;
						curY = e.originalEvent.touches[0].pageY
					}
					if(config.wipeMove) {
						triggerEvent(config.wipeMove, {
							curX : curX,
							curY : curY
						})
					}
				}
			}

			function touchCalculate(e) {
				var endDate = new Date().getTime();
				var ms = startDate - endDate;
				var x = curX;
				var y = curY;
				var dx = x - startX;
				var dy = y - startY;
				var ax = Math.abs(dx);
				var ay = Math.abs(dy);
				if(ax < 15 && ay < 15 && ms < 100) {
					clickEvent = false;
					if(config.preventDefault) {
						resetTouch();
						touchedElement.trigger("click");
						return
					}
				} else if(useMouseEvents) {
					var evts = touchedElement.data("events");
					if(evts) {
						var clicks = evts.click;
						if(clicks && clicks.length > 0) {
							$.each(clicks, function(i, f) {
								clickEvent = f;
								return
							});
							touchedElement.unbind("click")
						}
					}
				}
				var toright = dx > 0;
				var tobottom = dy > 0;
				var s = ((ax + ay) * 60) / ((ms) / 6 * (ms));
				if(s < 1)
					s = 1;
				if(s > 5)
					s = 5;
				var result = {
					speed : parseInt(s),
					x : ax,
					y : ay,
					source : touchedElement
				};
				if(ax >= config.moveX) {
					if(config.allowDiagonal && ay >= config.moveY) {
						if(toright && tobottom) {
							triggerEvent(config.wipeDownRight, result)
						} else if(toright && !tobottom) {
							triggerEvent(config.wipeUpRight, result)
						} else if(!toright && tobottom) {
							triggerEvent(config.wipeDownLeft, result)
						} else {
							triggerEvent(config.wipeUpLeft, result)
						}
					} else if(ax >= ay) {
						if(toright) {
							triggerEvent(config.wipeRight, result)
						} else {
							triggerEvent(config.wipeLeft, result)
						}
					}
				} else if(ay >= config.moveY && ay > ax) {
					if(tobottom) {
						triggerEvent(config.wipeDown, result)
					} else {
						triggerEvent(config.wipeUp, result)
					}
				}
				resetTouch()
			}

			function resetTouch() {
				startX = false;
				startY = false;
				startDate = false;
				isMoving = false;
				if(clickEvent) {
					window.setTimeout(function() {
						touchedElement.bind("click", clickEvent);
						clickEvent = false
					}, 50)
				}
			}

			function triggerEvent(wipeEvent, result) {
				if(wipeEvent) {
					wipeEvent(result)
				}
			}

			if("ontouchstart" in document.documentElement) {
				$(this).bind("touchstart", onTouchStart);
				$(this).bind("touchend", onTouchEnd)
			} else {
				useMouseEvents = true;
				$(this).bind("mousedown", onTouchStart);
				$(this).bind("mouseout", onTouchEnd)
			}
		});
		return this
	}
})(jQuery);

(function ($) {
    var types = ['DOMMouseScroll', 'mousewheel'];
    if ($.event.fixHooks) {
        for (var i = types.length; i;) {
            $.event.fixHooks[types[--i]] = $.event.mouseHooks
        }
    }
    $.event.special.mousewheel = {
        setup: function () {
            if (this.addEventListener) {
                for (var i = types.length; i;) {
                    this.addEventListener(types[--i], handler, false)
                }
            } else {
                this.onmousewheel = handler
            }
        },
        teardown: function () {
            if (this.removeEventListener) {
                for (var i = types.length; i;) {
                    this.removeEventListener(types[--i], handler, false)
                }
            } else {
                this.onmousewheel = null
            }
        }
    };
    $.fn.extend({
        mousewheel: function (fn) {
            return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel")
        },
        unmousewheel: function (fn) {
            return this.unbind("mousewheel", fn)
        }
    });

    function handler(event) {
        var orgEvent = event || window.event,
            args = [].slice.call(arguments, 1),
            delta = 0,
            returnValue = true,
            deltaX = 0,
            deltaY = 0;
        event = $.event.fix(orgEvent);
        event.type = "mousewheel";
        if (orgEvent.wheelDelta) {
            delta = orgEvent.wheelDelta / 120
        }
        if (orgEvent.detail) {
            delta = -orgEvent.detail / 3
        }
        deltaY = delta;
        if (orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS) {
            deltaY = 0;
            deltaX = -1 * delta
        }
        if (orgEvent.wheelDeltaY !== undefined) {
            deltaY = orgEvent.wheelDeltaY / 120
        }
        if (orgEvent.wheelDeltaX !== undefined) {
            deltaX = -1 * orgEvent.wheelDeltaX / 120
        }
        args.unshift(event, delta, deltaX, deltaY);
        return ($.event.dispatch || $.event.handle).apply(this, args)
    }
})(jQuery);
/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 */
jQuery.easing["jswing"] = jQuery.easing["swing"];
jQuery.extend(jQuery.easing, {
    def: "easeOutQuad",
    swing: function (a, b, c, d, e) {
        return jQuery.easing[jQuery.easing.def](a, b, c, d, e)
    },
    easeInQuad: function (a, b, c, d, e) {
        return d * (b /= e) * b + c
    },
    easeOutQuad: function (a, b, c, d, e) {
        return -d * (b /= e) * (b - 2) + c
    },
    easeInOutQuad: function (a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b + c;
        return -d / 2 * (--b * (b - 2) - 1) + c
    },
    easeInCubic: function (a, b, c, d, e) {
        return d * (b /= e) * b * b + c
    },
    easeOutCubic: function (a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b + 1) + c
    },
    easeInOutCubic: function (a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b * b + c;
        return d / 2 * ((b -= 2) * b * b + 2) + c
    },
    easeInQuart: function (a, b, c, d, e) {
        return d * (b /= e) * b * b * b + c
    },
    easeOutQuart: function (a, b, c, d, e) {
        return -d * ((b = b / e - 1) * b * b * b - 1) + c
    },
    easeInOutQuart: function (a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b * b * b + c;
        return -d / 2 * ((b -= 2) * b * b * b - 2) + c
    },
    easeInQuint: function (a, b, c, d, e) {
        return d * (b /= e) * b * b * b * b + c
    },
    easeOutQuint: function (a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b * b * b + 1) + c
    },
    easeInOutQuint: function (a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b * b * b * b + c;
        return d / 2 * ((b -= 2) * b * b * b * b + 2) + c
    },
    easeInSine: function (a, b, c, d, e) {
        return -d * Math.cos(b / e * (Math.PI / 2)) + d + c
    },
    easeOutSine: function (a, b, c, d, e) {
        return d * Math.sin(b / e * (Math.PI / 2)) + c
    },
    easeInOutSine: function (a, b, c, d, e) {
        return -d / 2 * (Math.cos(Math.PI * b / e) - 1) + c
    },
    easeInExpo: function (a, b, c, d, e) {
        return b == 0 ? c : d * Math.pow(2, 10 * (b / e - 1)) + c
    },
    easeOutExpo: function (a, b, c, d, e) {
        return b == e ? c + d : d * (-Math.pow(2, - 10 * b / e) + 1) + c
    },
    easeInOutExpo: function (a, b, c, d, e) {
        if (b == 0) return c;
        if (b == e) return c + d;
        if ((b /= e / 2) < 1) return d / 2 * Math.pow(2, 10 * (b - 1)) + c;
        return d / 2 * (-Math.pow(2, - 10 * --b) + 2) + c
    },
    easeInCirc: function (a, b, c, d, e) {
        return -d * (Math.sqrt(1 - (b /= e) * b) - 1) + c
    },
    easeOutCirc: function (a, b, c, d, e) {
        return d * Math.sqrt(1 - (b = b / e - 1) * b) + c
    },
    easeInOutCirc: function (a, b, c, d, e) {
        if ((b /= e / 2) < 1) return -d / 2 * (Math.sqrt(1 - b * b) - 1) + c;
        return d / 2 * (Math.sqrt(1 - (b -= 2) * b) + 1) + c
    },
    easeInElastic: function (a, b, c, d, e) {
        var f = 1.70158;
        var g = 0;
        var h = d;
        if (b == 0) return c;
        if ((b /= e) == 1) return c + d;
        if (!g) g = e * .3;
        if (h < Math.abs(d)) {
            h = d;
            var f = g / 4
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        return -(h * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - f) * 2 * Math.PI / g)) + c
    },
    easeOutElastic: function (a, b, c, d, e) {
        var f = 1.70158;
        var g = 0;
        var h = d;
        if (b == 0) return c;
        if ((b /= e) == 1) return c + d;
        if (!g) g = e * .3;
        if (h < Math.abs(d)) {
            h = d;
            var f = g / 4
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        return h * Math.pow(2, - 10 * b) * Math.sin((b * e - f) * 2 * Math.PI / g) + d + c
    },
    easeInOutElastic: function (a, b, c, d, e) {
        var f = 1.70158;
        var g = 0;
        var h = d;
        if (b == 0) return c;
        if ((b /= e / 2) == 2) return c + d;
        if (!g) g = e * .3 * 1.5;
        if (h < Math.abs(d)) {
            h = d;
            var f = g / 4
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        if (b < 1) return -.5 * h * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - f) * 2 * Math.PI / g) + c;
        return h * Math.pow(2, - 10 * (b -= 1)) * Math.sin((b * e - f) * 2 * Math.PI / g) * .5 + d + c
    },
    easeInBack: function (a, b, c, d, e, f) {
        if (f == undefined) f = 1.70158;
        return d * (b /= e) * b * ((f + 1) * b - f) + c
    },
    easeOutBack: function (a, b, c, d, e, f) {
        if (f == undefined) f = 1.70158;
        return d * ((b = b / e - 1) * b * ((f + 1) * b + f) + 1) + c
    },
    easeInOutBack: function (a, b, c, d, e, f) {
        if (f == undefined) f = 1.70158;
        if ((b /= e / 2) < 1) return d / 2 * b * b * (((f *= 1.525) + 1) * b - f) + c;
        return d / 2 * ((b -= 2) * b * (((f *= 1.525) + 1) * b + f) + 2) + c
    },
    easeInBounce: function (a, b, c, d, e) {
        return d - jQuery.easing.easeOutBounce(a, e - b, 0, d, e) + c
    },
    easeOutBounce: function (a, b, c, d, e) {
        if ((b /= e) < 1 / 2.75) {
            return d * 7.5625 * b * b + c
        } else if (b < 2 / 2.75) {
            return d * (7.5625 * (b -= 1.5 / 2.75) * b + .75) + c
        } else if (b < 2.5 / 2.75) {
            return d * (7.5625 * (b -= 2.25 / 2.75) * b + .9375) + c
        } else {
            return d * (7.5625 * (b -= 2.625 / 2.75) * b + .984375) + c
        }
    },
    easeInOutBounce: function (a, b, c, d, e) {
        if (b < e / 2) return jQuery.easing.easeInBounce(a, b * 2, 0, d, e) * .5 + c;
        return jQuery.easing.easeOutBounce(a, b * 2 - e, 0, d, e) * .5 + d * .5 + c
    }
});
;(function(a, b, c, d) {
	var e = c(a), f = c(b), g = c.lightbox = function() {
		g.open.apply(this, arguments)
	}, h = c.support.opacity, i = b.createTouch !== d, j = null, k = function(a) {
		return a && a.hasOwnProperty && a instanceof c
	}, l = function(a) {
		return a && c.type(a) === "string"
	}, m = function(a) {
		return l(a) && a.indexOf("%") > 0
	}, n = function(a) {
		var b = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
		if (b.test(a))
			return true
	}, o = function(a) {
		return a && !(a.style.overflow && a.style.overflow === "hidden") && (a.clientWidth && a.scrollWidth > a.clientWidth || a.clientHeight && a.scrollHeight > a.clientHeight)
	}, p = function(a, b) {
		var c = parseInt(a, 10);
		if (b && m(a)) {
			c = g.getViewport()[b] / 100 * c
		}
		return Math.ceil(c)
	}, q = function(a, b) {
		return p(a, b) + "px"
	}, r = function(a, b, d) {
		d = d || "";
		if (c.type(d) === "object") {
			d = c.param(d, true)
		}
		c.each(b, function(b, c) {
			a = a.replace("$" + b, c || "")
		});
		if (d.length) {
			a += (a.indexOf("?") > 0 ? "&" : "?") + d
		}
		return a
	};
	c.extend(g, {
		defaults : {
			padding : [11, 11, 8, 11],
			margin : 20,
			width : 800,
			height : 600,
			minWidth : 100,
			minHeight : 100,
			maxWidth : 9999,
			maxHeight : 9999,
			autoSize : true,
			autoHeight : true,
			autoWidth : true,
			autoResize : !i,
			autoCenter : !i,
			fitToView : true,
			maintainRatio : false,
			topRatio : .5,
			leftRatio : .5,
			scrolling : "auto",
			wrapCSS : "",
			arrows : true,
			closeBtn : true,
			closeClick : false,
			nextClick : false,
			mouseWheel : false,
			autoPlay : false,
			interval : 3e3,
			preload : 3,
			modal : false,
			cyclic : true,
			ajax : {
				dataType : "html",
				headers : {
					"X-lightBox" : true
				}
			},
			iframe : {
				scrolling : "auto",
				preload : true
			},
			swf : {
				wmode : "transparent",
				allowfullscreen : "true",
				allowscriptaccess : "always"
			},
			keys : {
				next : {
					39 : "left",
					40 : "up"
				},
				prev : {
					37 : "right",
					38 : "down"
				},
				close : [27],
				play : [32],
				toggle : [70]
			},
			direction : {
				next : "left",
				prev : "right"
			},
			scrollOutside : true,
			index : 0,
			type : null,
			href : null,
			content : null,
			title : null,
			template : {
				wrap : '<div class="lightbox-wrap" tabIndex="-1"><div class="lightbox-skin"><div class="lightbox-outer"><div class="lightbox-inner"></div></div><div class="lightbox-utility"><div></div></div><div class="clear"></div></div></div>',
				image : '<img class="lightbox-image" src="{href}" alt="" />',
				iframe : '<iframe id="lightbox-frame{rnd}" name="lightbox-frame{rnd}" class="lightbox-iframe" frameborder="0" vspace="0" hspace="0"' + (c.browser.msie ? ' allowtransparency="true"' : "") + "></iframe>",
				error : '<p class="lightbox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
				closeBtn : '<a title="Close" class="lightbox-item lightbox-close" href="javascript:;"></a>',
				next : '<a title="Next" class="lightbox-nav lightbox-next" href="javascript:;"><span></span></a>',
				prev : '<a title="Previous" class="lightbox-nav lightbox-prev" href="javascript:;"><span></span></a>'
			},
			transitionIn : "elastic",
			transitionInSpeed : 250,
			transitionInEasing : "swing",
			transitionInOpacity : true,
			transitionInEffect : "zoomIn",
			transitionOut : "elastic",
			transitionOutSpeed : 250,
			transitionOutEasing : "swing",
			transitionOutOpacity : true,
			transitionOutEffect : "zoomOut",
			transitionNext : "shuffle",
			transitionNextSpeed : 500,
			transitionNextEasing : "easeInBack",
			transitionNextEffect : "changeIn",
			transitionPrev : "shuffle",
			transitionPrevSpeed : 500,
			transitionPrevEasing : "easeOutBack",
			transitionPrevEffect : "changeOut",
			theme : "alt",
			addons : {
				overlay : {
					closeClick : true,
					speedOut : 200,
					showEarly : true,
					css : {}
				},
				title : {
					type : "inside"
				},
				media : {},
				buttons : {}
			},
			beforeLoadInternal : function() {
				var a = this.element, b = this;
				a.attr("data-option") && c.each(a.attr("data-option").split(";"), function(a, c) {
					var d = c.match(/\s*([A-Z_]*?)\s*:\s*(.+)\s*/i);
					d[2] = d && n(d[2]) ? parseInt(d[2]) : d[2];
					d && (b[d[1]] = d[2])
				});
				this == b;
				if (a.data("titan-width"))
					this.width = a.data("titan-width");
				if (a.data("titan-height"))
					this.height = a.data("titan-height");
				if (a.data("titan-theme"))
					this.theme = a.data("titan-theme");
				if (a.data("titan-modal"))
					this.modal = a.data("titan-modal")
			},
			beforeShowInternal : function() {
				this.theme != "default" && c("body").find(".lightbox-wrap").addClass(this.theme)
			},
			onCancel : c.noop,
			beforeLoad : c.noop,
			afterLoad : c.noop,
			beforeShow : c.noop,
			afterShow : c.noop,
			beforeChange : c.noop,
			beforeClose : c.noop,
			afterClose : c.noop
		},
		group : {},
		opts : {},
		previous : null,
		coming : null,
		current : null,
		isActive : false,
		isOpen : false,
		isOpened : false,
		wrap : null,
		skin : null,
		utility : null,
		outer : null,
		inner : null,
		player : {
			timer : null,
			isActive : false
		},
		ajaxLoad : null,
		imgPreload : null,
		transitions : {},
		addons : {},
		open : function(a, b) {
			if (!a) {
				return
			}
			if (!c.isPlainObject(b)) {
				b = {}
			}
			if (false === g.close(true)) {
				return
			}
			if (!c.isArray(a)) {
				a = k(a) ? c(a).get() : [a]
			}
			c.each(a, function(e, f) {
				var h = {}, i, j, m, n, o, p, q;
				if (c.type(f) === "object") {
					if (f.nodeType) {
						f = c(f)
					}
					if (k(f)) {
						h = {
							href : f.attr("href"),
							title : f.attr("title"),
							isDom : true,
							element : f
						};
						if (c.metadata) {
							c.extend(true, h, f.metadata())
						}
					} else {
						h = f
					}
				}
				i = b.href || h.href || (l(f) ? f : null);
				j = b.title !== d ? b.title : h.title || "";
				m = b.content || h.content;
				n = m ? "html" : b.type || h.type;
				if (!n && h.isDom) {
					n = f.data("lightbox-type");
					if (!n) {
						o = f.prop("class").match(/lightbox\.(\w+)/);
						n = o ? o[1] : null
					}
				}
				if (l(i)) {
					if (!n) {
						if (g.isImage(i)) {
							n = "image"
						} else if (g.isSWF(i)) {
							n = "swf"
						} else if (i.charAt(0) === "#") {
							n = "inline"
						} else if (g.isFrame(i)) {
							n = "iframe"
						} else if (l(f)) {
							n = "html";
							m = f
						} else {
							n = "ajax"
						}
					}
					if (n === "ajax") {
						p = i.split(/\s+/, 2);
						i = p.shift();
						q = p.shift()
					}
				}
				if (!m) {
					if (n === "inline") {
						if (i) {
							m = c(l(i) ? i.replace(/.*(?=#[^\s]+$)/, "") : i)
						} else if (h.isDom) {
							m = f
						}
					} else if (n === "html") {
						m = i
					} else if (!n && !i && h.isDom) {
						n = "inline";
						m = f
					}
				}
				c.extend(h, {
					href : i,
					type : n,
					content : m,
					title : j,
					selector : q
				});
				a[e] = h
			});
			g.opts = c.extend(true, {}, g.defaults, b);
			if (b.keys !== d) {
				g.opts.keys = b.keys ? c.extend({}, g.defaults.keys, b.keys) : false
			}
			g.group = a;
			return g._start(g.opts.index)
		},
		cancel : function() {
			var a = g.coming;
			if (!a || false === g.trigger("onCancel")) {
				return
			}
			g.hideLoading();
			if (g.ajaxLoad) {
				g.ajaxLoad.abort()
			}
			g.ajaxLoad = null;
			if (g.imgPreload) {
				g.imgPreload.onload = g.imgPreload.onerror = null
			}
			if (a.wrap) {
				a.wrap.stop(true).trigger("onReset").remove()
			}
			if (!g.current) {
				g.trigger("afterClose")
			}
			g.coming = null
		},
		close : function(a) {
			g.cancel();
			if (false === g.trigger("beforeClose")) {
				return
			}
			g.unbindEvents();
			if (!g.isOpen || a === true) {
				c(".lightbox-wrap").stop(true).trigger("onReset").remove();
				g._afterZoomOut()
			} else {
				g.isOpen = g.isOpened = false;
				g.isClosing = true;
				c(".lightbox-item, .lightbox-nav").remove();
				g.wrap.stop(true, true).removeClass("lightbox-opened");
				if (g.wrap.css("position") === "fixed") {
					g.wrap.css(g._getPosition(true))
				}
				g.transitions[g.current.transitionOutEffect]()
			}
		},
		play : function(a) {
			var b = function() {
				clearTimeout(g.player.timer)
			}, d = function() {
				b();
				if (g.current && g.player.isActive) {
					g.player.timer = setTimeout(g.next, g.current.interval)
				}
			}, e = function() {
				b();
				c("body").unbind(".player");
				g.player.isActive = false;
				g.trigger("onPlayEnd")
			}, f = function() {
				if (g.current && (g.current.cyclic || g.current.index < g.group.length - 1)) {
					g.player.isActive = true;
					c("body").bind({
						"afterShow.player onUpdate.player" : d,
						"onCancel.player beforeClose.player" : e,
						"beforeLoad.player" : b
					});
					d();
					g.trigger("onPlayStart")
				}
			};
			if (a === true || !g.player.isActive && a !== false) {
				f()
			} else {
				e()
			}
		},
		next : function(a) {
			var b = g.current;
			if (b) {
				if (!l(a)) {
					a = b.direction.next
				}
				g.jumpto(b.index + 1, a, "next")
			}
		},
		prev : function(a) {
			var b = g.current;
			if (b) {
				if (!l(a)) {
					a = b.direction.prev
				}
				g.jumpto(b.index - 1, a, "prev")
			}
		},
		jumpto : function(a, b, c) {
			var e = g.current;
			if (!e) {
				return
			}
			a = p(a);
			g.direction = b || e.direction[a >= e.index ? "next" : "prev"];
			g.router = c || "jumpto";
			if (e.cyclic) {
				if (a < 0) {
					a = e.group.length + a % e.group.length
				}
				a = a % e.group.length
			}
			if (e.group[a] !== d) {
				g.cancel();
				g._start(a)
			}
		},
		reposition : function(a, b) {
			var c;
			if (g.isOpen) {
				c = g._getPosition(b);
				if (a && a.type === "scroll") {
					delete c.position;
					g.wrap.stop(true, true).animate(c, 200)
				} else {
					g.wrap.css(c)
				}
			}
		},
		update : function(a) {
			var b = a && a.type, c = !b || b === "orientationchange";
			if (c) {
				clearTimeout(j);
				j = null
			}
			if (!g.isOpen || j) {
				return
			}
			if (c || i) {
				g.wrap.removeAttr("style").addClass("lightbox-tmp");
				g.trigger("onUpdate")
			}
			j = setTimeout(function() {
				var c = g.current;
				if (!c) {
					return
				}
				g.wrap.removeClass("lightbox-tmp");
				if (b !== "scroll") {
					g._setDimension()
				}
				if (!(b === "scroll" && c.canShrink)) {
					g.reposition(a)
				}
				g.trigger("onUpdate");
				j = null
			}, i ? 500 : c ? 20 : 300)
		},
		toggle : function(a) {
			if (g.isOpen) {
				g.current.fitToView = c.type(a) === "boolean" ? a : !g.current.fitToView;
				g.update()
			}
		},
		hideLoading : function() {
			f.unbind("keypress.lb");
			c("#lightbox-loading").remove()
		},
		showLoading : function() {
			var a, b;
			g.hideLoading();
			f.bind("keypress.lb", function(a) {
				if ((a.which || a.keyCode) === 27) {
					a.preventDefault();
					g.cancel()
				}
			});
			a = c('<div id="lightbox-loading"><div></div></div>').click(g.cancel).appendTo("body");
			if (!g.defaults.fixed) {
				b = g.getViewport();
				a.css({
					position : "absolute",
					top : b.h * .5 + b.y,
					left : b.w * .5 + b.x
				})
			}
		},
		getViewport : function() {
			var b = g.current ? g.current.locked : false, c = {
				x : e.scrollLeft(),
				y : e.scrollTop()
			};
			if (b) {
				c.w = b[0].clientWidth;
				c.h = b[0].clientHeight
			} else {
				c.w = i && a.innerWidth ? a.innerWidth : e.width();
				c.h = i && a.innerHeight ? a.innerHeight : e.height()
			}
			return c
		},
		unbindEvents : function() {
			if (g.wrap && k(g.wrap)) {
				g.wrap.unbind(".lb")
			}
			f.unbind(".lb");
			e.unbind(".lb")
		},
		bindEvents : function() {
			var a = g.current, b;
			if (!a) {
				return
			}
			e.bind("orientationchange.lb" + ( i ? "" : " resize.lb") + (a.autoCenter && !a.locked ? " scroll.lb" : ""), g.update);
			b = a.keys;
			if (b) {
				f.bind("keydown.lb", function(e) {
					var f = e.which || e.keyCode, h = e.target || e.srcElement;
					if (!e.ctrlKey && !e.altKey && !e.shiftKey && !e.metaKey && !(h && (h.type || c(h).is("[contenteditable]")))) {
						c.each(b, function(b, h) {
							if (a.group.length > 1 && h[f] !== d) {
								g[b](h[f]);
								e.preventDefault();
								return false
							}
							if (c.inArray(f, h) > -1) {
								g[b]();
								e.preventDefault();
								return false
							}
						})
					}
				})
			}
			if (i) {
				$wrapel = g.wrap;
				$wrapel.wipetouch({
					wipeLeft : function() {
						g.next("right")
					},
					wipeRight : function() {
						g.prev("left")
					}
				})
			}
			if (c.fn.mousewheel && a.mouseWheel) {
				g.wrap.bind("mousewheel.lb", function(b, d, e, f) {
					var h = b.target || null, i = c(h), j = false;
					while (i.length) {
						if (j || i.is(".lightbox-skin") || i.is(".lightbox-wrap")) {
							break
						}
						j = o(i[0]);
						i = c(i).parent()
					}
					if (d !== 0 && !j) {
						if (g.group.length > 1 && !a.canShrink) {
							if (f > 0 || e > 0) {
								g.prev(f > 0 ? "down" : "left")
							} else if (f < 0 || e < 0) {
								g.next(f < 0 ? "up" : "right")
							}
							b.preventDefault()
						}
					}
				})
			}
		},
		trigger : function(a, b) {
			var d, e = b || g.coming || g.current;
			if (!e) {
				return
			}
			if (c.isFunction(e[a])) {
				d = e[a].apply(e, Array.prototype.slice.call(arguments, 1))
			}
			if (d === false) {
				return false
			}
			if (a === "onCancel" && !g.isOpened) {
				g.isActive = false
			}
			if (e.addons) {
				c.each(e.addons, function(b, d) {
					if (d && g.addons[b] && c.isFunction(g.addons[b][a])) {
						g.addons[b][a](d, e)
					}
				})
			}
			c.event.trigger(a + ".lb")
		},
		isImage : function(a) {
			return l(a) && a.match(/\.(jp(e|g|eg)|gif|png|bmp|webp)((\?|#).*)?$/i)
		},
		isFrame : function(a) {
			return l(a) && a.indexOf("http://") != -1 && a.indexOf(location.hostname.toLowerCase()) == -1
		},
		isSWF : function(a) {
			return l(a) && a.match(/\.(swf)((\?|#).*)?$/i)
		},
		_start : function(a) {
			var b = {}, d, e, f, h, j;
			a = p(a);
			d = g.group[a] || null;
			if (!d) {
				return false
			}
			b = c.extend(true, {}, g.opts, d);
			h = b.margin;
			j = b.padding;
			if (c.type(h) === "number") {
				b.margin = [h, h, h, h]
			}
			if (c.type(j) === "number") {
				b.padding = [j, j, j, j]
			}
			if (b.modal) {
				c.extend(true, b, {
					nextClick : false,
					arrows : false,
					mouseWheel : false,
					keys : null,
					addons : {
						overlay : {
							closeClick : false
						}
					}
				})
			}
			if (b.autoSize) {
				b.autoWidth = b.autoHeight = true
			}
			if (b.width === "auto") {
				b.autoWidth = true
			}
			if (b.height === "auto") {
				b.autoHeight = true
			}
			b.group = g.group;
			b.index = a;
			g.coming = b;
			if (false === g.trigger("beforeLoadInternal")) {
				g.coming = null;
				return
			}
			if (false === g.trigger("beforeLoad")) {
				g.coming = null;
				return
			}
			f = b.type;
			e = b.href;
			if (!f) {
				g.coming = null;
				if (g.current && g.router && g.router !== "jumpto") {
					g.current.index = a;
					return g[g.router](g.direction)
				}
				return false
			}
			g.isActive = true;
			if (f === "image" || f === "swf") {
				b.autoHeight = b.autoWidth = false;
				b.scrolling = "visible"
			}
			if (f === "image") {
				b.maintainRatio = true
			}
			if (f === "iframe" && i) {
				b.scrolling = "scroll"
			}
			b.wrap = c(b.template.wrap).addClass("lightbox-" + ( i ? "mobile" : "desktop") + " lightbox-type-" + f + " lightbox-tmp " + b.wrapCSS).appendTo(b.parent);
			c.extend(b, {
				skin : c(".lightbox-skin", b.wrap),
				utility : c(".lightbox-utility", b.wrap),
				outer : c(".lightbox-outer", b.wrap),
				inner : c(".lightbox-inner", b.wrap)
			});
			c.each(["Top", "Right", "Bottom", "Left"], function(a, c) {
				b.skin.css("padding" + c, q(b.padding[a]))
			});
			g.trigger("onReady");
			if (f === "inline" || f === "html") {
				if (!b.content || !b.content.length) {
					return g._error("content")
				}
			} else if (!e) {
				return g._error("href")
			}
			if (f === "image") {
				g._loadImage()
			} else if (f === "ajax") {
				g._loadAjax()
			} else if (f === "iframe") {
				g._loadIframe()
			} else {
				g._afterLoad()
			}
		},
		_error : function(a) {
			c.extend(g.coming, {
				type : "html",
				autoWidth : true,
				autoHeight : true,
				minWidth : 0,
				minHeight : 0,
				scrolling : "no",
				hasError : a,
				content : g.coming.template.error
			});
			g._afterLoad()
		},
		_loadImage : function() {
			var a = g.imgPreload = new Image;
			a.onload = function() {
				this.onload = this.onerror = null;
				g.coming.width = this.width;
				g.coming.height = this.height;
				g._afterLoad()
			};
			a.onerror = function() {
				this.onload = this.onerror = null;
				g._error("image")
			};
			a.src = g.coming.href;
			if (a.complete === d || !a.complete) {
				g.showLoading()
			}
		},
		_loadAjax : function() {
			var a = g.coming;
			g.showLoading();
			g.ajaxLoad = c.ajax(c.extend({}, a.ajax, {
				url : a.href,
				cache : false,
				error : function(a, b) {
					if (g.coming && b !== "abort") {
						g._error("ajax", a)
					} else {
						g.hideLoading()
					}
				},
				success : function(b, c) {
					if (c === "success") {
						a.content = b;
						g._afterLoad()
					}
				}
			}))
		},
		_loadIframe : function() {
			var a = g.coming, b = c(a.template.iframe.replace(/\{rnd\}/g, (new Date).getTime())).attr("scrolling", i ? "auto" : a.iframe.scrolling).attr("src", a.href);
			c(a.wrap).bind("onReset", function() {
				try {
					c(this).find("iframe").hide().attr("src", "//about:blank").end().empty()
				} catch(a) {
				}
			});
			if (a.iframe.preload) {
				g.showLoading();
				b.one("load", function() {
					c(this).data("ready", 1);
					if (!i) {
						c(this).bind("load.lb", g.update)
					}
					c(this).parents(".lightbox-wrap").width("100%").removeClass("lightbox-tmp").show();
					g._afterLoad()
				})
			}
			a.content = b.appendTo(a.inner);
			if (!a.iframe.preload) {
				g._afterLoad()
			}
		},
		_preloadImages : function() {
			var a = g.group, b = g.current, c = a.length, d = b.preload ? Math.min(b.preload, c - 1) : 0, e, f;
			for ( f = 1; f <= d; f += 1) {
				e = a[(b.index + f) % c];
				if (e.type === "image" && e.href) {
					(new Image).src = e.href
				}
			}
		},
		_afterLoad : function() {
			var a = g.coming, b = g.current, d = "lightbox-placeholder", e, f, h, i, j, l;
			g.hideLoading();
			if (!a || g.isActive === false) {
				return
			}
			if (false === g.trigger("afterLoad", a, b)) {
				a.wrap.stop(true).trigger("onReset").remove();
				g.coming = null;
				return
			}
			if (b) {
				g.trigger("beforeChange", b);
				b.wrap.stop(true).removeClass("lightbox-opened").find(".lightbox-item, .lightbox-nav").remove();
				if (b.wrap.css("position") === "fixed") {
					b.wrap.css(g._getPosition(true))
				}
			}
			g.unbindEvents();
			e = a;
			f = a.content;
			h = a.type;
			i = a.scrolling;
			c.extend(g, {
				wrap : e.wrap,
				skin : e.skin,
				utility : e.utility,
				outer : e.outer,
				inner : e.inner,
				current : e,
				previous : b
			});
			j = e.href;
			switch(h) {
				case"inline":
				case"ajax":
				case"html":
					if (e.selector) {
						f = c("<div>").html(f).find(e.selector)
					} else if (k(f)) {
						if (!f.data(d)) {
							f.data(d, c('<div class="' + d + '"></div>').insertAfter(f).hide())
						}
						f = f.show().detach();
						e.wrap.bind("onReset", function() {
							if (c(this).find(f).length) {
								f.hide().replaceAll(f.data(d)).data(d, false)
							}
						})
					}
					break;
				case"image":
					f = e.template.image.replace("{href}", j);
					break;
				case"swf":
					f = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="' + j + '"></param>';
					l = "";
					c.each(e.swf, function(a, b) {
						f += '<param name="' + a + '" value="' + b + '"></param>';
						l += " " + a + '="' + b + '"'
					});
					f += '<embed src="' + j + '" type="application/x-shockwave-flash" width="100%" height="100%"' + l + "></embed></object>";
					break
			}
			if (!(k(f) && f.parent().is(e.inner))) {
				e.inner.append(f)
			}
			g.trigger("beforeShowInternal");
			g.trigger("beforeShow");
			e.inner.css("overflow", i === "yes" ? "scroll" : i === "no" ? "hidden" : i);
			g._setDimension();
			e.wrap.removeClass("lightbox-tmp");
			e.pos = c.extend({}, e.dim, g._getPosition(true));
			g.isOpen = false;
			g.coming = null;
			g.bindEvents();
			if (!g.isOpened) {
				c(".lightbox-wrap").not(e.wrap).stop(true).trigger("onReset").remove()
			} else if (b.transitionPrevEffect) {
				g.transitions[b.transitionPrevEffect]()
			}
			g.transitions[g.isOpened?e.transitionNextEffect:e.transitionInEffect]();
			g._preloadImages()
		},
		_setDimension : function() {
			var a = g.getViewport(), b = 0, d = false, e = false, f = g.wrap, h = g.skin, i = g.utility, j = g.inner, k = g.current, l = k.width, n = k.height, o = k.minWidth, r = k.minHeight, s = k.maxWidth, t = k.maxHeight, u = k.scrolling, v = k.scrollOutside ? k.scrollbarWidth : 0, w = k.margin, x = w[1] + w[3], y = w[0] + w[2], z, A, B, C, D, E, F, G, H, I, J, K, M, N, O;
			f.add(h).add(j).width("auto").height("auto");
			z = h.outerWidth(true) - h.width();
			A = h.outerHeight(true) - h.height();
			B = x + z;
			C = y + A;
			D = m(l) ? (a.w - B) * p(l) / 100 : l;
			E = m(n) ? (a.h - C) * p(n) / 100 : n;
			if (k.type === "iframe") {
				N = k.content;
				if (k.autoHeight && N.data("ready") === 1) {
					try {
						if (N[0].contentWindow.document.location) {
							j.width(D).height(9999);
							O = N.contents().find("body");
							if (v) {
								O.css("overflow-x", "hidden")
							}
							E = O.height()
						}
					} catch(P) {
					}
				}
			} else if (k.autoWidth || k.autoHeight) {
				j.addClass("lightbox-tmp");
				if (!k.autoWidth) {
					j.width(D)
				}
				if (!k.autoHeight) {
					j.height(E)
				}
				if (k.autoWidth) {
					D = j.width()
				}
				if (k.autoHeight) {
					E = j.height()
				}
				j.removeClass("lightbox-tmp")
			}
			l = p(D);
			n = p(E);
			H = D / E;
			o = p(m(o) ? p(o, "w") - B : o);
			s = p(m(s) ? p(s, "w") - B : s);
			r = p(m(r) ? p(r, "h") - C : r);
			t = p(m(t) ? p(t, "h") - C : t);
			F = s;
			G = t;
			K = a.w - x;
			M = a.h - y;
			if (k.maintainRatio) {
				if (l > s) {
					l = s;
					n = l / H
				}
				if (n > t) {
					n = t;
					l = n * H
				}
				if (l < o) {
					l = o;
					n = l / H
				}
				if (n < r) {
					n = r;
					l = n * H
				}
			} else {
				l = Math.max(o, Math.min(l, s));
				n = Math.max(r, Math.min(n, t))
			}
			if (k.fitToView) {
				s = Math.min(a.w - B, s);
				t = Math.min(a.h - C, t);
				j.width(p(l)).height(p(n));
				f.width(p(l + z));
				I = f.width();
				J = f.height();
				if (k.maintainRatio) {
					while ((I > K || J > M) && l > o && n > r) {
						if (b++ > 19) {
							break
						}
						n = Math.max(r, Math.min(t, n - 10));
						l = n * H;
						if (l < o) {
							l = o;
							n = l / H
						}
						if (l > s) {
							l = s;
							n = l / H
						}
						j.width(p(l)).height(p(n));
						f.width(p(l + z));
						I = f.width();
						J = f.height()
					}
				} else {
					l = Math.max(o, Math.min(l, l - (I - K)));
					n = Math.max(r, Math.min(n, n - (J - M)))
				}
			}
			if (v && u === "auto" && n < E && l + z + v < K) {
				l += v
			}
			j.width(p(l)).height(p(n));
			f.width(p(l + z));
			I = f.width();
			J = f.height();
			d = (I > K || J > M) && l > o && n > r;
			e = k.maintainRatio ? l < F && n < G && l < D && n < E : (l < F || n < G) && (l < D || n < E);
			c.extend(k, {
				dim : {
					width : q(I),
					height : q(J)
				},
				origWidth : D,
				origHeight : E,
				canShrink : d,
				canExpand : e,
				wPadding : z,
				hPadding : A,
				wrapSpace : J - h.outerHeight(true),
				skinSpace : h.height() - n
			});
			if (!N && k.autoHeight && n > r && n < t && !e) {
				j.height("auto")
			}
		},
		_getPosition : function(a) {
			var b = g.current, c = g.getViewport(), d = b.margin, e = g.wrap.width() + d[1] + d[3], f = g.wrap.height() + d[0] + d[2], h = {
				position : "absolute",
				top : d[0],
				left : d[3]
			};
			if (b.autoCenter && b.fixed && !a && f <= c.h && e <= c.w) {
				h.position = "fixed"
			} else if (!b.locked) {
				h.top += c.y;
				h.left += c.x
			}
			h.top = q(Math.max(h.top, h.top + (c.h - f) * b.topRatio));
			h.left = q(Math.max(h.left, h.left + (c.w - e) * b.leftRatio));
			return h
		},
		_afterZoomIn : function() {
			var a = g.current;
			if (!a) {
				return
			}
			g.isOpen = g.isOpened = true;
			g.wrap.addClass("lightbox-opened").css("overflow", "visible");
			g.reposition();
			if (a.closeClick || a.nextClick) {
				g.inner.css("cursor", "pointer").bind("click.lb", function(b) {
					if (!c(b.target).is("a") && !c(b.target).parent().is("a")) {
						g[a.closeClick?"close":"next"]()
					}
				})
			}
			if (a.closeBtn) {
				c(a.template.closeBtn).appendTo(g.skin).bind("click.lb", g.close)
			}
			if (a.arrows && g.group.length > 1) {
				if (a.cyclic || a.index > 0) {
					c(a.template.prev).appendTo(g.outer).bind("click.lb", g.prev)
				}
				if (a.cyclic || a.index < g.group.length - 1) {
					c(a.template.next).appendTo(g.outer).bind("click.lb", g.next)
				}
			}
			g.trigger("afterShow");
			if (!a.cyclic && a.index === a.group.length - 1) {
				g.play(false)
			} else if (g.opts.autoPlay && !g.player.isActive) {
				g.opts.autoPlay = false;
				g.play()
			}
		},
		_afterZoomOut : function() {
			var a = g.current;
			c(".lightbox-wrap").stop(true).trigger("onReset").remove();
			c.extend(g, {
				group : {},
				opts : {},
				router : false,
				current : null,
				isActive : false,
				isOpened : false,
				isOpen : false,
				isClosing : false,
				wrap : null,
				skin : null,
				utility : null,
				outer : null,
				inner : null
			});
			g.trigger("afterClose", a)
		}
	});
	g.transitions = {
		getOrigPosition : function() {
			var a = g.current, b = a.element, c = a.orig, d = {}, e = 50, f = 50, h = a.hPadding, i = a.wPadding, j = g.getViewport();
			if (!c && a.isDom && b.is(":visible")) {
				c = b.find("img:first");
				if (!c.length) {
					c = b
				}
			}
			if (k(c)) {
				d = c.offset();
				if (c.is("img")) {
					e = c.outerWidth();
					f = c.outerHeight()
				}
			} else {
				d.top = j.y + (j.h - f) * a.topRatio;
				d.left = j.x + (j.w - e) * a.leftRatio
			}
			if (a.locked) {
				d.top -= j.y;
				d.left -= j.x
			}
			d = {
				top : q(d.top - h * a.topRatio),
				left : q(d.left - i * a.leftRatio),
				width : q(e + i),
				height : q(f + h)
			};
			return d
		},
		step : function(a, b) {
			var c, d, e, f = b.prop, h = g.current, i = h.wrapSpace, j = h.skinSpace;
			if (f === "width" || f === "height") {
				c = b.end === b.start ? 1 : (a - b.start) / (b.end - b.start);
				if (g.isClosing) {
					c = 1 - c
				}
				d = f === "width" ? h.wPadding : h.hPadding;
				e = a - d;
				g.skin[f](p(f === "width" ? e : e - i * c));
				g.inner[f](p(f === "width" ? e : e - i * c - j * c))
			}
		},
		zoomIn : function() {
			var a = g.current, b = a.pos, d = a.transitionIn, e = d === "elastic", f = c.extend({
				opacity : 1
			}, b);
			delete f.position;
			if (e) {
				b = this.getOrigPosition();
				if (a.transitionInOpacity) {
					b.opacity = .1
				}
			} else if (d === "fade") {
				b.opacity = .1
			}
			g.wrap.css(b).animate(f, {
				duration : d === "none" ? 0 : a.transitionInSpeed,
				easing : a.transitionInEasing,
				step : e ? this.step : null,
				complete : g._afterZoomIn
			})
		},
		zoomOut : function() {
			var a = g.current, b = a.transitionOut, c = b === "elastic", d = {
				opacity : .1
			};
			if (c) {
				d = this.getOrigPosition();
				if (a.transitionOutOpacity) {
					d.opacity = .1
				}
			}
			g.wrap.animate(d, {
				duration : b === "none" ? 0 : a.transitionOutSpeed,
				easing : a.transitionOutEasing,
				step : c ? this.step : null,
				complete : g._afterZoomOut
			})
		},
		changeIn : function() {
			var a = g.current, b = a.transitionNext, c = a.pos, d = {
				opacity : 1
			}, e = g.direction, f = 200, h;
			c.opacity = .1;
			if (b === "shuffle") {
				h = e === "down" || e === "up" ? "top" : "left";
				if (e === "down" || e === "right") {
					c[h] = q(p(c[h]) - f);
					d[h] = "+=" + f + "px"
				} else {
					c[h] = q(p(c[h]) + f);
					d[h] = "-=" + f + "px"
				}
			}
			if (b === "none") {
				g._afterZoomIn()
			} else {
				g.wrap.css(c).animate(d, {
					duration : a.transitionNextSpeed,
					easing : a.transitionNextEasing,
					complete : g._afterZoomIn
				})
			}
		},
		changeOut : function() {
			var a = g.previous, b = a.transitionPrev, d = {
				opacity : .1
			}, e = g.direction, f = 200;
			if (b === "shuffle") {
				d[e === "down" || e === "up" ? "top" : "left"] = (e === "up" || e === "left" ? "-" : "+") + "=" + f + "px"
			}
			a.wrap.animate(d, {
				duration : b === "none" ? 0 : a.transitionPrevSpeed,
				easing : a.transitionPrevEasing,
				complete : function() {
					c(this).trigger("onReset").remove()
				}
			})
		}
	};
	g.addons.media = {
		types : {
			youtube : {
				matcher : /(youtube\.com|youtu\.be)\/(watch\?v=|v\/|u\/|embed)?([\w-]{11}|\?listType=(.*)&list=(.*)).*/i,
				params : {
					autoplay : 1,
					autohide : 1,
					fs : 1,
					rel : 0,
					hd : 1,
					wmode : "opaque",
					enablejsapi : 1
				},
				type : "iframe",
				url : "//www.youtube.com/embed/$3"
			},
			vimeo : {
				matcher : /(?:vimeo(?:pro)?.com)\/(?:[^\d]+)?(\d+)(?:.*)/,
				params : {
					autoplay : 1,
					hd : 1,
					show_title : 1,
					show_byline : 1,
					show_portrait : 0,
					color : "",
					fullscreen : 1
				},
				type : "iframe",
				url : "//player.vimeo.com/video/$1"
			},
			metacafe : {
				matcher : /metacafe.com\/(?:watch|fplayer)\/([\w\-]{1,10})/,
				params : {
					autoPlay : "yes"
				},
				type : "swf",
				url : function(a, b, d) {
					d.swf.flashVars = "playerVars=" + c.param(b, true);
					return "//www.metacafe.com/fplayer/" + a[1] + "/.swf"
				}
			},
			dailymotion : {
				matcher : /dailymotion.com\/video\/(.*)\/?(.*)/,
				params : {
					additionalInfos : 0,
					autoPlay : 1
				},
				type : "iframe",
				url : "//www.dailymotion.com/embed/video/$1"
			},
			telly : {
				matcher : /telly\.com\/([a-zA-Z0-9_\-\?\=]+)/i,
				params : {
					autoplay : 1
				},
				type : "iframe",
				url : "//www.telly.com/embed.php?guid=$1"
			},
			twitpic : {
				matcher : /twitpic\.com\/(?!(?:place|photos|events)\/)([a-zA-Z0-9\?\=\-]+)/i,
				type : "image",
				url : "//twitpic.com/show/full/$1/"
			},
			instagram : {
				matcher : /(instagr\.am|instagram\.com)\/p\/([a-zA-Z0-9_\-]+)\/?/i,
				type : "image",
				url : "//$1/p/$2/media/"
			},
			google_maps : {
				matcher : /maps\.google\.([a-z]{2,3}(\.[a-z]{2})?)\/(\?ll=|maps\?)(.*)/i,
				type : "iframe",
				url : function(a) {
					return "//maps.google." + a[1] + "/" + a[3] + "" + a[4] + "&output=" + (a[4].indexOf("layer=c") > 0 ? "svembed" : "embed")
				}
			}
		},
		beforeLoad : function(a, b) {
			var d = b.href || "", e = false, f, g, h, i;
			for (f in this.types) {
				g = this.types[f];
				h = d.match(g.matcher);
				if (h) {
					e = g.type;
					i = c.extend(true, {}, g.params, b[f] || (c.isPlainObject(a[f]) ? a[f].params : null));
					d = c.type(g.url) === "function" ? g.url.call(this, h, i, b) : r(g.url, h, i);
					break
				}
			}
			if (e) {
				b.href = d;
				b.type = e;
				b.autoHeight = false
			}
		}
	};
	g.addons.buttons = {
		template : '<div id="lightbox-buttons"><ul><li><a class="btnPrev" title="Previous" href="javascript:;"></a></li><li><a class="btnNext" title="Next" href="javascript:;"></a></li></ul></div>',
		list : null,
		buttons : null,
		skinOffset : null,
		skinTop : null,
		skinleft : null,
		skinWidth : null,
		skinHeight : null,
		buttonTop : null,
		buttonLeft : null,
		beforeLoad : function(a, b) {
			if (b.group.length < 2) {
				b.addons.buttons = false;
				b.closeBtn = true;
				return
			}
		},
		onPlayStart : function() {
			if (this.buttons) {
				this.buttons.play.attr("title", "Pause slideshow").addClass("btnPlayOn")
			}
		},
		onPlayEnd : function() {
			if (this.buttons) {
				this.buttons.play.attr("title", "Start slideshow").removeClass("btnPlayOn")
			}
		},
		beforeShow : function(a, b) {
			if (this.list)
				this.list.remove()
		},
		afterShow : function(a, b) {
			var d = this.buttons;
			if (c("body").find("#lightbox-buttons").length < 1) {
				this.list = c(a.template || this.template).addClass(a.position || "top").prependTo(g.utility.find("> div"));
				d = {
					prev : this.list.find(".btnPrev").click(g.prev),
					next : this.list.find(".btnNext").click(g.next),
					play : this.list.find(".btnPlay").click(g.play).addClass(g.player.isActive ? "btnPlayOn" : "")
				}
			}
			c(g.wrap).addClass("lightbox-gallery");
			g._setDimension();
			if (b.index > 0 || b.cyclic) {
				d.prev.removeClass("btnDisabled")
			} else {
				d.prev.addClass("btnDisabled")
			}
			if (b.cyclic || b.index < b.group.length - 1) {
				d.next.removeClass("btnDisabled");
				d.play.removeClass("btnDisabled")
			} else {
				d.next.addClass("btnDisabled");
				d.play.addClass("btnDisabled")
			}
			this.buttons = d;
			this.onUpdate(a, b)
		},
		onUpdate : function(a, b) {
		},
		beforeClose : function() {
			if (this.list) {
				this.list.remove()
			}
			this.list = null;
			this.buttons = null
		}
	};
	g.addons.overlay = {
		overlay : null,
		update : function() {
			var a = "100%", d;
			this.overlay.width(a).height("100%");
			if (c.browser.msie) {
				d = Math.max(b.documentElement.offsetWidth, b.body.offsetWidth);
				if (f.width() > d) {
					a = f.width()
				}
			} else if (f.width() > e.width()) {
				a = f.width()
			}
			this.overlay.width(a).height(f.height())
		},
		onReady : function(a, d) {
			c(".lightbox-overlay").stop(true, true);
			if (!this.overlay) {
				c.extend(this, {
					overlay : c('<div class="lightbox-overlay"></div>').appendTo(d.parent),
					margin : f.height() > e.height() || c("body").css("overflow-y") === "scroll" ? c("body").css("margin-right") : false,
					el : b.all && !b.querySelector ? c("html") : c("body")
				})
			}
			if (d.fixed && !i) {
				this.overlay.addClass("lightbox-overlay-fixed");
				if (d.autoCenter) {
					this.overlay.append(d.wrap);
					d.locked = this.overlay
				}
			}
			if (a.showEarly === true) {
				this.beforeShow.apply(this, arguments)
			}
		},
		beforeShow : function(a, b) {
			var d = this.overlay.unbind(".lb").width("auto").height("auto").css(a.css);
			if (a.closeClick) {
				d.bind("click.lb", function(a) {
					if (c(a.target).hasClass("lightbox-overlay")) {
						g.close()
					}
				})
			}
			if (b.fixed && !i) {
				if (b.locked) {
					this.el.addClass("lightbox-lock");
					if (this.margin !== false) {
						c("body").css("margin-right", p(this.margin) + b.scrollbarWidth)
					}
				}
			} else {
				this.update()
			}
			d.show()
		},
		onUpdate : function(a, b) {
			if (!b.fixed || i) {
				this.update()
			}
		},
		afterClose : function(a) {
			var b = this, d = a.speedOut || 0;
			if (b.overlay && !g.isActive) {
				b.overlay.fadeOut(d || 0, function() {
					c("body").css("margin-right", b.margin);
					b.el.removeClass("lightbox-lock");
					b.overlay.remove();
					b.overlay = null
				})
			}
		}
	};
	g.addons.title = {
		beforeShow : function(a) {
			var b = g.current.title, d = a.type, e, f;
			if (!l(b) || c.trim(b) === "") {
				return
			}
			e = c('<div class="lightbox-title lightbox-title-' + d + '-wrap">' + b + "</div>");
			switch(d) {
				case"inside":
					f = g.utility.find("> div");
					break;
				default:
					break
			}
			e.appendTo(f)
		}
	};
	c.fn.lightbox = function(a) {
		var b, d = c(this), e = this.selector || "", h = function(f) {
			var h = c(this).blur(), i = b, j, k;
			if (!(f.ctrlKey || f.altKey || f.shiftKey || f.metaKey) && !h.is(".lightbox-wrap")) {
				j = a.groupAttr || "data-titan-group";
				k = h.attr(j);
				if (!k) {
					j = "rel";
					k = h.get(0)[j]
				}
				if (k && k !== "" && k !== "nofollow") {
					h = e.length ? c(e) : d;
					h = h.filter("[" + j + '="' + k + '"]');
					i = h.index(this)
				}
				a.index = i;
				if (g.open(h, a) !== false) {
					f.preventDefault()
				}
			}
		};
		a = a || {};
		b = a.index || 0;
		if (!e || a.live === false) {
			d.unbind("click.lb-start").bind("click.lb-start", h)
		} else {
			f.undelegate(e, "click.lb-start").delegate(e + ":not('.lightbox-item, .lightbox-nav')", "click.lb-start", h)
		}
		return this
	};
	f.ready(function() {
		if (c.scrollbarWidth === d) {
			c.scrollbarWidth = function() {
				var a = c('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo("body"), b = a.children(), d = b.innerWidth() - b.height(99).innerWidth();
				a.remove();
				return d
			}
		}
		if (c.support.fixedPosition === d) {
			c.support.fixedPosition = function() {
				var a = c('<div style="position:fixed;top:20px;"></div>').appendTo("body"), b = a[0].offsetTop === 20 || a[0].offsetTop === 15;
				a.remove();
				return b
			}()
		}
		c.extend(g.defaults, {
			scrollbarWidth : c.scrollbarWidth(),
			fixed : c.support.fixedPosition,
			parent : c("body")
		})
	})
})(window, document, jQuery); 