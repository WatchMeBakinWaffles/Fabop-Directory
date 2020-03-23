// Ajax function to append the content of the form to create new tags
$('#btn-add-tag').click(function() {
    $.ajax({
        url: URL+"/manager/tags/modal/new",
        method: "GET"
    }).done(function(content){
        appendContentToModal(content);
    })
});

// Ajax function to append the content of the form to create new performance
$('#btn-add-performance').click(function() {
    $.ajax({
        url: URL+"/manager/performances/new",
        method: "get"
    }).done(function(content){
        appendContentToModal(content);
    })
});

function appendContentToModal(content){
    $('.modal-body').empty();
    $('.modal-body').append(content);
}
