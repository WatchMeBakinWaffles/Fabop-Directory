/* If the current path of the page is matching the regex, ajax queries are send */

// TODO :: Changer les fonctions et utilisations du web components

if (path.match(/\/manager\/people\/[0-9]/g) != null) {
    // Ajax function to append the list of existing tags
    $.ajax({
        url: URL+"/manager/tags/list",
        method: "get"
    }).done(function(content){
        $('#tags_affect_tag').append(content);
    })

    // Ajax function to append the list of existing performances
    $.ajax({
        url: URL+"/manager/performances/list",
        method: "get"
    }).done(function(content){
        $('#tags_affect_performance').append(content);
    })
}
