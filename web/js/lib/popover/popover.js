/**
 * @file  下挂框
 */

/**
 *
 * @param {[type]} hookHtmlId   要挂载的divid
 * @param {[type]} contentDivId 下拉框中要显示的内容的div
 * @param {[type]} config       配置，现支持close和success两个，分别关联提交与关闭两个按钮
 * @param {[type]} func         要在下拉框中添加的响应函数
 */
var Popover = function (hookHtmlId, contentDivId, config, func) {
    var pub = {
        mHookId: '',
        mContent: '',
        mContentDivId: '',
        mConfig: {},
        mFunc: {},
        mNowFunc: [],
        mPreId: '',
        mNowId: '',
        hook: function () {
            if ([] === $('#' + hookHtmlId) || [] === $('#' + contentDivId)) {
                return false;
            }
            this.mFunc = func;
            this.mConfig = config || {};
            this.mHookId = hookHtmlId;
            this.mContentDivId = contentDivId;
            this.mContent = $('#' + contentDivId).html();
            this.initHookAttr();
            this.bindEvent();
        },
        initHookAttr: function () {
            var hookId = '#' + this.mHookId;
            $(hookId).attr('data-content', this.mContent);
        },
        bindEvent: function () {
            var that = this;
            $(document).on('click', '#' + this.mHookId, function (e) {
                that.releasEvent();
                that._preFunc = that.mNowFunc;
                that.mNowFunc = [];
                var tmp = $('#' + that.mHookId).attr('aria-describedby');
                if ('undefined' !== typeof tmp) {
                    that.mNowId = tmp;
                    that.beforeSuccess();
                    that.bindClientEvent();
                    that.afterSuccess();
                    that.bindClose();
                } else {
                    that.mNowId = '';
                }
                $(document).on('click', function (e) {
                    if ($('#' + that.mNowId).length !== 0
                        && $(e.target).closest('.popover').attr('id') !== that.mNowId
                        && $(e.target).attr('id') !== that.mHookId
                        && that.mNowId !== '') {
                        that.closePop();
                        return false;
                    }
                });
            });
            // $(document).click(function (e) {
            //     if ($('#' + that.mNowId).length !== 0 && !$('#' + that.mHookId).is(e.target) &&
            //         !$('#' + that.mNowId).is(e.target) && that.mConfig['close']) {
            //         $('#' + that.mHookId).click();
            //         //console.log('123');
            //     }
            // });
        },
        closePop: function () {
            $('#' + this.mNowId).remove();
            this.mNowId = null;
            $('#' + this.mHookId).click();
            this.releasEvent();
        },
        bindClose: function () {
            var that = this;
            var config = this.mConfig;
            if ('undefined' !== config['close']) {
                var divId = '#' + that.mNowId + ' #' + config['close'];
                that.mNowFunc.push = divId;
                $(document).on('click', divId, function () {
                    that.closePop();
                });
            }
        },
        releasEvent: function () {
            if ([] !== this.mNowFunc) {
                for (var i in this.mNowFunc) {
                    $(document).off('click', '#' + this.mNowId + ' ' + this.mNowFunc[i]);
                }
            }
        },
        bindClientEvent: function () {
            var that = this;
            for (var selector in this.mFunc) {
                if ('object' === typeof this.mFunc[selector] && [] !== $(selector)) {
                    for (var funType in this.mFunc[selector]) {
                        if ('function' === typeof this.mFunc[selector][funType]) {
                            var divId = '#' + that.mNowId + ' ' + selector;
                            that.mNowFunc.push = divId;
                            $(document).on(funType, divId, that.mFunc[selector][funType]);
                        }
                    }
                }
            }
        },
        beforeSuccess: function () {
            var that = this;
            var config = this.mConfig;
            if ('undefined' !== config['success']) {
                var divId = '#' + that.mNowId + ' #' + config['success'];
                that.mNowFunc.push = divId;
                $(document).on('click', divId, function () {
                    if ('' !== that.mNowId) {
                        $('#' + that.mContentDivId).html($('#' + that.mNowId + ' .popover-content').html());
                        $('#' + that.mHookId).attr('data-content', $('#' + that.mNowId + ' .popover-content').html());
                    }
                });
            }
        },
        afterSuccess: function () {
            var that = this;
            var config = this.mConfig;
            if ('undefined' !== config['success']) {
                var divId = '#' + that.mNowId + ' #' + config['success'];
                that.mNowFunc.push = divId;
                $(document).on('click', divId, function () {
                    $('#' + that.mHookId).click();
                });
            }
        }
    };
    pub.hook();
    return pub;
};