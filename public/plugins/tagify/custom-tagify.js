// The DOM element you wish to replace with Tagify
var input = document.querySelector('input[name=basic]');

// initialize Tagify on the above input node reference
new Tagify(input)


/**
 * 
 * Mix Text & Tags
 *  
 **/ 

// Define two types of whitelists, each used for the dropdown suggestions menu,
// depending on the prefix pattern typed (@/#). See settings below.
var whitelist_1 = [
    { value: 100, text: 'React', title: 'facebook' },
    { value: 200, text: 'Vue', title: 'Kelly Young' },
    { value: 300, text: 'Angular', title: 'Google' },
    { value: 400, text: 'Svelte', title: 'Rich Harris' },
    { value: 500, text: 'NextJS', title: 'Vercel' },
    { value: 600, text: 'Python', title: 'Guido van Rossum' },
    { value: 700, text: 'PHP', title: 'Rasmus Lerdorf' },
    { value: 800, text: 'Strapi', title: 'Open Source' },
    { value: 900, text: 'Wordpress', title: 'Automatic' },
    { value: 1000, text: 'Typescript', title: "Microsoft" }
  ]

// Second whitelist, which is shown only when starting to type "#".
// Thiw whitelist is the most simple one possible.
var whitelist_2 = ['Microsoft', 'Apple', 'Google', 'Samsung', 'Esty', 'Github', 'Amazon', 'Ebay', 'FedEx', 'Facebook'];


// initialize Tagify
var input = document.querySelector('[name=mix]'),
    // init Tagify script on the above inputs
    mixMatch = new Tagify(input, {
    //  mixTagsInterpolator: ["{{", "}}"],
        mode: 'mix',  // <--  Enable mixed-content
        pattern: /@|#/,  // <--  Text starting with @ or # (if single, String can be used here)
        tagTextProp: 'text',  // <-- the default property (from whitelist item) for the text to be rendered in a tag element.
        // Array for initial interpolation, which allows only these tags to be used
        whitelist: whitelist_1.concat(whitelist_2).map(function(item){
            return typeof item == 'string' ? {value:item} : item
        }),
        dropdown : {
            enabled: 1,
            position: 'text', // <-- render the suggestions list next to the typed text ("caret")
            mapValueTo: 'text', // <-- similar to above "tagTextProp" setting, but for the dropdown items
            highlightFirst: true  // automatically highlights first sugegstion item in the dropdown
        },
        callbacks: {
            add: console.log,  // callback when adding a tag
            remove: console.log   // callback when removing a tag
        }
    })


// A good place to pull server suggestion list accoring to the prefix/value
mixMatch.on('input', function(e){
    var prefix = e.detail.prefix;

    // first, clean the whitlist array, because the below code, while not, might be async,
    // therefore it should be up to you to decide WHEN to render the suggestions dropdown
    // tagify.settings.whitelist.length = 0;

    if( prefix ){
        if( prefix == '@' )
            mixMatch.whitelist = whitelist_1;

        if( prefix == '#' )
            mixMatch.whitelist = whitelist_2;

        if( e.detail.value.length > 1 )
            mixMatch.dropdown.show(e.detail.value);
    }

    console.log( mixMatch.value )
    console.log('mix-mode "input" event value: ', e.detail)
})

mixMatch.on('add', function(e){
    console.log(e)
})



/**
 * 
 * Render Suggestion
 *  
 **/ 

 var input = document.querySelector('input[name=tags3]'),
 tagify = new Tagify(input, {
     pattern             : /^.{0,20}$/,  // Validate typed tag(s) by Regex. Here maximum chars length is defined as "20"
     delimiters          : ",| ",        // add new tags when a comma or a space character is entered
     keepInvalidTags     : true,         // do not remove invalid tags (but keep them marked as invalid)
     editTags            : {
         clicks: 2,              // single click to edit a tag
         keepInvalid: false      // if after editing, tag is invalid, auto-revert
     },
     maxTags             : 6,
     blacklist           : ["foo", "bar", "baz"],
     whitelist           : ["React","Angular","TypeScript","Vue","Python","Wordpress","Svelte","PHP","Javascript","HTML","CSS","JSON","XML","Markdown","Go","C++","C#","Java","Docker","Perl","Ruby","Rust","NodeJS","SQL","SQLite","MonogDB","MYSQL","Swift","SCSS","SASS"],
     transformTag        : transformTag,
     backspace           : "edit",
     placeholder         : "Type something",
     dropdown : {
         enabled: 1,            // show suggestion after 1 typed character
         fuzzySearch: false,    // match only suggestions that starts with the typed characters
         position: 'text',      // position suggestions list next to typed text
         caseSensitive: true,   // allow adding duplicate items if their case is different
     },
     templates: {
         dropdownItemNoMatch: function(data) {
             return `<div class='${this.settings.classNames.dropdownItem}' tabindex="0" role="option">
                 No suggestion found for: <strong>${data.value}</strong>
             </div>`
         }
     }
 })

// generate a random color (in HSL format, which I like to use)
function getRandomColor(){
 function rand(min, max) {
     return min + Math.random() * (max - min);
 }

 var h = rand(1, 360)|0,
     s = rand(40, 70)|0,
     l = rand(65, 72)|0;

 return 'hsl(' + h + ',' + s + '%,' + l + '%)';
}

function transformTag( tagData ){
 tagData.color = getRandomColor();
 tagData.style = "--tag-bg:" + tagData.color;

 if( tagData.value.toLowerCase() == 'shit' )
     tagData.value = 's✲✲t'
}

tagify.on('add', function(e){
 console.log(e.detail)
})

tagify.on('invalid', function(e){
 console.log(e, e.detail);
})

var clickDebounce;

tagify.on('click', function(e){
 const {tag:tagElm, data:tagData} = e.detail;

 // a delay is needed to distinguish between regular click and double-click.
 // this allows enough time for a possible double-click, and noly fires if such
 // did not occur.
 clearTimeout(clickDebounce);
 clickDebounce = setTimeout(() => {
     tagData.color = getRandomColor();
     tagData.style = "--tag-bg:" + tagData.color;
     tagify.replaceTag(tagElm, tagData);
 }, 200);
})

tagify.on('dblclick', function(e){
 // when souble clicking, do not change the color of the tag
 clearTimeout(clickDebounce);
})






/**
 * 
 * Users List
 *  
 **/ 


// https://www.mockaroo.com/


var inputElm = document.querySelector('input[name=users-list-tags]');

function tagTemplate(tagData){
    return `
        <tag title="${tagData.email}"
                contenteditable='false'
                spellcheck='false'
                tabIndex="-1"
                class="tagify__tag ${tagData.class ? tagData.class : ""}"
                ${this.getAttributes(tagData)}>
            <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
            <div>
                <div class='tagify__tag__avatar-wrap'>
                    <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
                </div>
                <span class='tagify__tag-text'>${tagData.name}</span>
            </div>
        </tag>
    `
}

function suggestionItemTemplate(tagData){
    return `
        <div ${this.getAttributes(tagData)}
            class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'
            tabindex="0"
            role="option">
            ${ tagData.avatar ? `
            <div class='tagify__dropdown__item__avatar-wrap'>
                <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
            </div>` : ''
            }
            <strong>${tagData.name}</strong>
            <span>${tagData.email}</span>
        </div>
    `
}

// initialize Tagify on the above input node reference
var usrList = new Tagify(inputElm, {
    tagTextProp: 'name', // very important since a custom template is used with this property as text
    enforceWhitelist: true,
    skipInvalid: true, // do not remporarily add invalid tags
    dropdown: {
        closeOnSelect: false,
        enabled: 0,
        classname: 'users-list',
        searchKeys: ['name', 'email']  // very important to set by which keys to search for suggesttions when typing
    },
    templates: {
        tag: tagTemplate,
        dropdownItem: suggestionItemTemplate
    },
    whitelist: [
        {
            "value": 1,
            "name": "Justinian Hattersley",
            "avatar": "https://i.pravatar.cc/80?img=1",
            "email": "jhattersley0@ucsd.edu"
        },
        {
            "value": 2,
            "name": "Antons Esson",
            "avatar": "https://i.pravatar.cc/80?img=2",
            "email": "aesson1@ning.com"
        },
        {
            "value": 3,
            "name": "Ardeen Batisse",
            "avatar": "https://i.pravatar.cc/80?img=3",
            "email": "abatisse2@nih.gov"
        },
        {
            "value": 4,
            "name": "Graeme Yellowley",
            "avatar": "https://i.pravatar.cc/80?img=4",
            "email": "gyellowley3@behance.net"
        },
        {
            "value": 5,
            "name": "Dido Wilford",
            "avatar": "https://i.pravatar.cc/80?img=5",
            "email": "dwilford4@jugem.jp"
        },
        {
            "value": 6,
            "name": "Celesta Orwin",
            "avatar": "https://i.pravatar.cc/80?img=6",
            "email": "corwin5@meetup.com"
        },
        {
            "value": 7,
            "name": "Sally Main",
            "avatar": "https://i.pravatar.cc/80?img=7",
            "email": "smain6@techcrunch.com"
        },
        {
            "value": 8,
            "name": "Grethel Haysman",
            "avatar": "https://i.pravatar.cc/80?img=8",
            "email": "ghaysman7@mashable.com"
        },
        {
            "value": 9,
            "name": "Marvin Mandrake",
            "avatar": "https://i.pravatar.cc/80?img=9",
            "email": "mmandrake8@sourceforge.net"
        },
        {
            "value": 10,
            "name": "Corrie Tidey",
            "avatar": "https://i.pravatar.cc/80?img=10",
            "email": "ctidey9@youtube.com"
        },
        {
            "value": 11,
            "name": "foo",
            "avatar": "https://i.pravatar.cc/80?img=11",
            "email": "foo@bar.com"
        },
        {
            "value": 12,
            "name": "foo",
            "avatar": "https://i.pravatar.cc/80?img=12",
            "email": "foo.aaa@foo.com"
        },
    ]
})

usrList.on('dropdown:show dropdown:updated', onDropdownShow)
usrList.on('dropdown:select', onSelectSuggestion)

var addAllSuggestionsElm;

function onDropdownShow(e){
    var dropdownContentElm = e.detail.tagify.DOM.dropdown.content;

    if( usrList.suggestedListItems.length > 1 ){
        addAllSuggestionsElm = getAddAllSuggestionsElm();

        // insert "addAllSuggestionsElm" as the first element in the suggestions list
        dropdownContentElm.insertBefore(addAllSuggestionsElm, dropdownContentElm.firstChild)
    }
}

function onSelectSuggestion(e){
    if( e.detail.elm == addAllSuggestionsElm )
        usrList.dropdown.selectAll();
}

// create a "add all" custom suggestion element every time the dropdown changes
function getAddAllSuggestionsElm(){
    // suggestions items should be based on "dropdownItem" template
    return usrList.parseTemplate('dropdownItem', [{
            class: "addAll",
            name: "Add all",
            email: usrList.whitelist.reduce(function(remainingSuggestions, item){
                return usrList.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1
            }, 0) + " Members"
        }]
      )
}





/**
 * 
 *  Single Value Select
 * 
 */

var input = document.querySelector('input[name=tags-select-mode]'),
    tagify = new Tagify(input, {
        enforceWhitelist: true,
        mode : "select",
        whitelist: ["first option", "second option", "third option"],
        blacklist: ['foo', 'bar'],
    })

// bind events
tagify.on('add', onAddTag)
tagify.DOM.input.addEventListener('focus', onSelectFocus)

function onAddTag(e){
    console.log(e.detail)
}

function onSelectFocus(e){
    console.log(e)
}