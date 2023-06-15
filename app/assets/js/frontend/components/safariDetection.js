import Register from 'framework/common/utils/Register';

const safariDetection = () => {
    if (navigator.vendor
        && navigator.vendor.indexOf('Apple') > -1
        && navigator.userAgent
        && !navigator.userAgent.match('CriOS')
    ) {
        document.getElementsByTagName('html')[0].className += ' is-safari';
    } else {
        document.getElementsByTagName('html')[0].className += ' is-no-safari';
    }
};

(new Register()).registerCallback(safariDetection, 'safariDetection');
