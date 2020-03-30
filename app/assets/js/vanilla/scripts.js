// Side menu dropdown
$('.dropdown').on('show.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
});

$('.dropdown').on('hide.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
});
if (path.slice(-3)=="new")
document.getElementById('validForm').addEventListener('submit',function () {
    document.getElementById('validForm').disabled=true;
});
