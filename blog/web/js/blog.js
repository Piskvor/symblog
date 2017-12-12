var $collectionHolder;

// setup an "add a tag" link
var $addTagLink = jQuery('<a href="#" class="add_tag_link">Add a tag</a>');
var $newLinkLi = jQuery('<div></div>').append($addTagLink);

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<div></div>').append(newForm);
    $newLinkLi.before($newFormLi);
}

jQuery(document).ready(function($) {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('#blog_article_tags');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form
        addTagForm($collectionHolder, $newLinkLi);
    });

    $('.button-cancel').on('click', function (event) {
        var $t = $(event.target);
        var text = $t.text();
        if (confirm("Really " + text + "?")) {
            window.location = '.';
            event.preventDefault();
        }
    });
    $('.timeago').timeago();
});