/**
 * 为数组添加 removeByKey 函数，根据索引删除数组元素
 * 使用方法：array.removeByKey(arrayIndex)
 */
Array.prototype.removeByKey = function(i){
    if (isNaN(i) || i > this.length)
        return false;
    this.splice(i,1);
}
/**
 * 为数组添加 removeByValue 函数，根据名称删除数组元素
 * 使用方法：array.removeByValue(value)
 */
Array.prototype.removeByValue = function(value) {
    for (var i = 0; i < this.length; ++i) {
        if (this[i] == value) {
            this.splice(i, 1);
        }
    }
}
/**
 * 为数组添加 inArray 函数
 */
Array.prototype.inArray = function(value)　{
    var i;
    for (i=0; i < this.length; i++) {
        if (this[i] === value) {
            return true;
        }
    }
    return false;
};
/**
 * 动态加载 css 文件
 * @param url css 文件路径
 * @media link media 属性
 */
jQuery.getCSS = function(url, media) {
    link = jQuery(document.createElement('link')).attr({
        href: url,
        media: media || 'screen',
        type: 'text/css',
        rel: 'stylesheet'
    });
    if (jQuery("link[href='" + url + "']").length == 0) {
        link.appendTo('head');
    }
}; 