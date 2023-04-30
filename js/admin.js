/* DB FEATURES SCRIPTS */

// добавить input по нажатию кнопки

const dbFeaturesAdd = document.getElementById("db_features_add")
const dbFeaturesItems = document.getElementById("db_features_items")
const dbFeaturesItemsList = dbFeaturesItems.getElementsByClassName("db-features-item")
const dbFeaturesNum = document.getElementById("db_features_num")

dbFeaturesAdd.addEventListener( 'click', function(){

    const node = document.createElement("div")

    let num = dbFeaturesItemsList.length - 1

    dbFeaturesItems.insertBefore( node, dbFeaturesAdd )

    node.classList.add("db-features-item" )
    node.setAttribute("id",  "db-features-item-" + num )

    node.innerHTML = '<h3>' + dbFeaturesTexts[0] + '</h3>' +
        '<input type="file" name="img_' + num + '" />' +
        '<input type="hidden" name="img_id_' + num + '" value="" />' +
        '<h3>' + dbFeaturesTexts[1] + '</h3>' +
        '<input type="text" name="headline_' + num + '" id="db_features_headline_' + num + '" size="30" value="" />' +
        '<h3>' + dbFeaturesTexts[2] + '</h3>' +
        '<textarea name="text_' + num + '" id="db_features_text_' + num + '"></textarea>'

    wp.editor.initialize( "db_features_text_" + num , {
        tinymce: {
            wpautop: false,
            plugins : 'charmap colorpicker hr lists paste tabfocus textcolor fullscreen wordpress wpautoresize wpeditimage wpemoji wpgallery wplink wptextpattern',
            toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv,listbuttons',
            toolbar2: 'styleselect,strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
            textarea_rows : 10
        },
        quicktags: {
            buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'
        },
        mediaButtons: false
    } )

    dbFeaturesNum.value = num + 1

})