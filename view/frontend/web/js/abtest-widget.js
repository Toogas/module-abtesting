define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    /**
     * Add isInViewport fn
     * */
    $.fn.isInViewport = function() {
        let elementTop = $(this).offset().top,
            elementBottom = elementTop + $(this).outerHeight(),
            viewportTop = $(window).scrollTop(),
            viewportBottom = viewportTop + $(window).height();

        return elementBottom > viewportTop && elementTop < viewportBottom;
    };

    $.widget('toogas.abtest', {
        options: {
            id: null,
            countView: true,
            block: null
        },

        _create: function () {
            if(this.options.id) {
                this._getContent();
            }
        },

        _getContent: function () {
            /**
             * Generate random number to select content A or B.
             * Has to be generated here to avoid cache conflicts
             */
            this.options.block = Math.floor(Math.random() * 100) % 2 == 0 ? 1 : 2;
            let $elem = this.element;

            if(this.xhr) {
                this.xhr.abort();
            }

            let self = this;
            this.xhr = $.ajax({
                url: this.options.url,
                data: {
                    id: this.options.id,
                    block: this.options.block
                },
                dataType: "json",
                type: 'GET',
                success: function (res) {
                    if(res.error) {
                        /** escape quietly */
                        console.warn(res.message);
                        return false;
                    }

                    if(res.html) {
                        $elem.html(res.html);
                        self.options.block = res.block;
                        self._bindEvents();
                    }
                },
                error: function () {
                    /** escape quietly */
                    console.warn('Error fetching A/B test content.');
                }
            });
        },

        _bindEvents: function () {
            //View handler
            this._checkVisibility();
            let self = this;
            $(window).on('resize scroll', function() {
                if (self.element.isInViewport() && self.options.countView) {
                    self._checkVisibility();
                }
            });

            //Click handler
            let container = $(self.element)[0];
            document.addEventListener('click', function( event ) {
                if (container == event.target || container.contains(event.target)) {
                    self._countClick();
                }
            });
        },

        _checkVisibility: function() {
            if (this.element.isInViewport() && this.options.countView) {
                this.options.countView = false;
                if(this.viewXhr) {
                    this.viewXhr.abort();
                }
                let self = this;
                this.viewXhr = $.ajax({
                    url: this.options.statsUrl,
                    data: {
                        id: this.options.id,
                        block: this.options.block,
                        action: 'countView'
                    },
                    dataType: "json",
                    type: 'GET',
                    error: function (e) {
                        if(e.statusText != 'abort') {
                            /** escape quietly and enable count again */
                            console.warn('Error counting A/B test view.');
                            self.options.countView = true;
                        }
                    }
                });
            }
        },

        _countClick: function() {
            if(this.clickXhr) {
                this.clickXhr.abort();
            }
            let self = this;
            this.clickXhr = $.ajax({
                url: this.options.statsUrl,
                data: {
                    id: this.options.id,
                    block: this.options.block,
                    action: 'countClick'
                },
                dataType: "json",
                type: 'GET'
            });
        }
    });

    return $.toogas.abtest;
});
