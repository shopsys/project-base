import Register from 'framework/common/utils/Register';

(new Register()).registerCallback(() => {
    $('#js-terms-and-conditions-print').on('click', function () {
        window.frames['js-terms-and-conditions-frame'].print();
    });
}, 'legalConditions');
