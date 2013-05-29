/**
 * Html 元素事件效果（进入，离开 input 元素时的友好提示）
 */
function fnInputElementFriendlyHint() {
    $("input:text, input:password, textarea").addClass('blur').bind({
        focus: function() {
            $(this).removeClass('blur').addClass('focus');
        },
        blur: function() {
            $(this).removeClass('focus').addClass('blur');
        }
    });
}
// JQuery ready function
$(function() {
    fnInputElementFriendlyHint();
    // go top buttons
    var $goTop = $('#go-top-btns').hide();
    var scrollTop = 0;
    $(window).scroll(scrollHandler);
    function scrollHandler(){
        scrollTop = Math.max(document.documentElement.scrollTop - 200, document.body.scrollTop - 250);
        if(navigator.userAgent.indexOf('MSIE 6') < 0) scrollTop > 0 ? $goTop.fadeIn() : $goTop.fadeOut();
    };
})