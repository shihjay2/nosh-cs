/**
 * jQuery UI Toggleboxes 1.1 fix1
 *
 * Copyright (c) 2009 Michael Keck (http://www.michaelkeck.de/)
 *
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * This plugin is a port with modifications from the original
 * jQuery UI Accordion 1.7.1
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI/Accordion
 *
 *
 * Modifications:
 *
 *    Use this to get boxes like the accordians, but the
 *    opened boxes still stay opened and the closed boxes
 *    still stay closed.
 *    I know, that there are perhaps some nearly same plugins
 *    like mine. But note: mine uses the same css-styles as
 *    accordion and the intit is nearly the same.
 *
 *
 * Usage:
 *
 *      $("#toggleboxes1").toggleboxes({
 *          header: "h3",
 *          icons: {
 *              'header': 'ui-icon-triangle-1-s',
 *              'headerSelected': 'ui-icon-triangle-1-n'
 *         }
 *      });
 *      // ore use a different class
 *      $("#toggleboxes2").toggleboxes({
 *          header: "h3",
 *          boxclass: 'ui-togglebox',
 *          icons: {
 *              'header': 'ui-icon-triangle-1-s',
 *              'headerSelected': 'ui-icon-triangle-1-n'
 *         }
 *      });
 *
 *
 * Depends:
 *     ui.core.js
 */
 
(function($) {
  $.widget("ui.toggleboxes", {

    _init: function() {
        var t = this, o =  t.options;
        t.running = 0;
        t.element.addClass(o.boxclass + ' ui-widget ui-helper-reset');
        if (t.element[0].nodeName == 'UL') {
            t.element.children('li').addClass(o.boxclass + '-li-fix');
        }
        t.element.find(o.header).each(function() {
            var io = false;
            if ($(this).next().attr('class').indexOf('opened') !== -1) {
                io = true;
            }
            $(this).addClass(o.boxclass + '-header ui-helper-reset ui-state-default ui-corner-all')
                .bind('mouseenter.toggleboxes', function(){ $(this).addClass('ui-state-hover'); })
                .bind('mouseleave.toggleboxes', function(){ $(this).removeClass('ui-state-hover'); })
                .bind('focus.toggleboxes', function(){ $(this).addClass('ui-state-focus'); })
                .bind('blur.toggleboxes', function(){ $(this).removeClass('ui-state-focus'); });
            $(this).next()
                .addClass(o.boxclass + '-content ui-helper-reset ui-widget-content ui-corner-bottom');
            if (io) {
                $(this).next().addClass(o.boxclass + '-content-active');
                $('<span/>').addClass('ui-icon ' + o.icons.headerSelected).prependTo($(this));
                $(this).attr('aria-expanded','true');
            } else {
                $('<span/>').addClass('ui-icon ' + o.icons.header).prependTo($(this));
                $(this).attr('aria-expanded','false');
            }
            $(this).bind('keydown', function(event) { return t._keydown(event); });
            if (!$.browser.safari) {
                $(this).find('a').attr('tabIndex','-1');
            }
            if (o.event) {
                $(this).bind((o.event) + '.toggleboxes', function(event) { return t._clickHandler.call(t, event, this); });
            }
        });
        if ($.browser.msie) {
            t.element.find('a').css('zoom', '1');
        }
    },

    _keydown: function(event) {
        var t = this, o = t.options, keyCode = $.ui.keyCode;
        if (o.disabled || event.altKey || event.ctrlKey) {
            return;
        }
        var h = t.element.find(o.header);
        var l = h.length;
        var currentIndex = h.index(event.target);
        var toFocus = false;
        switch(event.keyCode) {
            case keyCode.RIGHT:
            case keyCode.DOWN:
                toFocus = h[(currentIndex + 1) % l];
                break;
            case keyCode.LEFT:
            case keyCode.UP:
                toFocus = h[(currentIndex - 1 + l) % l];
                break;
            case keyCode.SPACE:
            case keyCode.ENTER:
                return t._clickHandler({ target: event.target }, event.target);
        }
        if (toFocus) {
            $(event.target).attr('tabIndex','-1');
            $(toFocus).attr('tabIndex','0');
            toFocus.focus();
            return false;
        }
        return true;
    },

    _clickHandler: function(event, target) {
        var o = this.options;
        if (o.disabled) {
            return false;
        }
        var h = $(event.currentTarget || target);
        if ((h.attr('aria-expanded') == 'true')) {
            h.removeClass("ui-state-active ui-corner-top").addClass("ui-state-default ui-corner-all").find(".ui-icon").removeClass(o.icons.headerSelected).addClass(o.icons.header);
            var toHide = h.next(),
                data = {
                    options: o
                },
                toShow = $([]),
                down = 1;
            h.attr('aria-expanded', 'false');
        } else {
            h.removeClass("ui-state-active ui-corner-top").addClass("ui-state-default ui-corner-all").find(".ui-icon").removeClass(o.icons.header).addClass(o.icons.headerSelected);
            var toShow = h.next(),
                data = {
                    options: o
                },
                toHide = $([]),
                down = 0;
            h.attr('aria-expanded', 'true');
        }
        if (this.running) {
            return false;
        }
        this._toggle(toShow, toHide, data, down);
        return false;
    },

    destroy: function() {
        var t = this, o = t.options, h = t.element.find(o.headers);
        t.element.removeClass(o.boxclass + ' ui-widget ui-helper-reset').unbind('.toggleboxes').removeData('toggleboxes');
        h.unbind(".toggleboxes").removeClass(o.boxclass + '-header ui-helper-reset ui-state-default ui-corner-all ui-state-active ui-corner-top').removeAttr('aria-expanded').removeAttr('tabindex');
        h.find('a').removeAttr('tabindex');
        h.children('.ui-icon').remove();
        var c = h.next();
        c.css('display', '').removeClass('ui-helper-reset ui-widget-content ui-corner-bottom ' + o.boxclass + '-content ' + o.boxclass + '-content-active');
        if (o.autoHeight || o.fillHeight) {
            c.css('height', '');
        }
    },

    _setData: function(key, value) {
        $.widget.prototype._setData.apply(this, arguments);
    },

    _toggle: function(toShow, toHide, data, down) {
        var t = this, o = t.options;
        t.toShow = toShow;
        t.toHide = toHide;
        t.data = data;
        var complete = function() { if(!t) return; return t._completed.apply(t, arguments); };
        t._trigger('changestart', null, t.data);
        t.running = toHide.size() === 0 ? toShow.size() : toHide.size();
        if (o.animated) {
            var animOptions = {
                toShow: toShow,
                toHide: toHide,
                complete: complete,
                down: down,
                autoHeight: o.autoHeight || o.fillSpace
            };
            if (!o.proxied) {
                o.proxied = o.animated;
            }
            if (!o.proxiedDuration) {
                o.proxiedDuration = o.duration;
            }
            o.animated = $.isFunction(o.proxied) ? o.proxied(animOptions) : o.proxied;
            o.duration = $.isFunction(o.proxiedDuration) ? o.proxiedDuration(animOptions) : o.proxiedDuration;
            var animations = $.ui.toggleboxes.animations, duration = o.duration, easing = o.animated;
            if (!animations[easing]) {
                animations[easing] = function(options) {
                    this.slide(options, {
                        easing: easing,
                        duration: duration || 700
                    });
                };
            }
            animations[easing](animOptions);
        } else {
            toHide.hide();
            toShow.show();
            complete(true);
        }
        toHide.prev().attr('aria-expanded','false').attr('tabIndex', '-1').blur();
        toShow.prev().attr('aria-expanded','true').attr('tabIndex', '0').focus();
    },

    _completed: function(cancel) {
        var o = this.options;
        this.running = cancel ? 0 : --this.running;
        if (this.running) {
            return;
        }
        if (o.clearStyle) {
            this.toShow.add(this.toHide).css({
                height: '',
                overflow: ''
            });
        }
        this._trigger('change', null, this.data);
    }

  });

  $.extend($.ui.toggleboxes, {
    version: '1.1',
    defaults: {
        animated: 'slide',
        autoHeight: true,
        clearStyle: false,
        boxclass: 'ui-accordion',
        event: 'click',
        fillSpace: false,
        header: '> li > :first-child,> :not(li):even',
        icons: {
            header: 'ui-icon-triangle-1-e',
            headerSelected: 'ui-icon-triangle-1-s'
        },
        navigation: false,
        navigationFilter: function() {
            return this.href.toLowerCase() == location.href.toLowerCase();
        }
    },
    animations: {
        slide: function(options, additions) {
            options = $.extend({
                easing: 'swing',
                duration: 300
            }, options, additions);
            if ( !options.toHide.size() ) {
                options.toShow.animate({height: 'show'}, options);
                return;
            }
            if ( !options.toShow.size() ) {
                options.toHide.animate({height: 'hide'}, options);
                return;
            }
            var overflow = options.toShow.css('overflow'), percentDone, showProps = {}, hideProps = {}, fxAttrs = ['height', 'paddingTop', 'paddingBottom'], originalWidth, s = options.toShow;
            originalWidth = s[0].style.width;
            s.width( parseInt(s.parent().width(),10) - parseInt(s.css('paddingLeft'),10) - parseInt(s.css('paddingRight'),10) - (parseInt(s.css('borderLeftWidth'),10) || 0) - (parseInt(s.css('borderRightWidth'),10) || 0) );
            $.each(fxAttrs, function(i, prop) {
                hideProps[prop] = 'hide';
                var parts = ('' + $.css(options.toShow[0], prop)).match(/^([\d+-.]+)(.*)$/);
                showProps[prop] = {
                    value: parts[1],
                    unit: parts[2] || 'px'
                };
            });
            options.toShow.css({ height: 0, overflow: 'hidden' }).show();
            options.toHide.filter(':hidden').each(options.complete).end().filter(':visible').animate(hideProps,{
                step: function(now, settings) {
                    if (settings.prop == 'height') {
                        percentDone = (settings.now - settings.start) / (settings.end - settings.start);
                    }
                    options.toShow[0].style[settings.prop] = (percentDone * showProps[settings.prop].value) + showProps[settings.prop].unit;
                },
                duration: options.duration,
                easing: options.easing,
                complete: function() {
                    if (!options.autoHeight) {
                        options.toShow.css('height', '');
                    }
                    options.toShow.css('width', originalWidth);
                    options.toShow.css({overflow: overflow});
                    options.complete();
                }
            });

        },
        bounceslide: function(options) {
            this.slide(options, {
                easing: options.down ? 'easeOutBounce' : 'swing',
                duration: options.down ? 1000 : 200
            });
        },
        easeslide: function(options) {
            this.slide(options, {
                easing: 'easeinout',
                duration: 700
            });
        }
    }

  });
})(jQuery);
