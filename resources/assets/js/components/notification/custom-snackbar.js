/**
 * Add Notification - Shorthand function to add notification on click
 * 
 * @param {#ID or .class} selector 
 * @param {object} options 
 */

function add_notification(selector, options) {
    document.querySelector(selector).addEventListener('click', function() {
        Snackbar.show(options);
    })
}

// Default

add_notification('.default', {text: 'Example notification text.', duration: 100000})

// Position

add_notification('.top-left', {text: 'Example notification text.', pos: 'top-left'})
add_notification('.top-center', {text: 'Example notification text.', pos: 'top-center'})
add_notification('.top-right', {text: 'Example notification text.', pos: 'top-right'})
add_notification('.bottom-left', {text: 'Example notification text.', pos: 'bottom-left'})
add_notification('.bottom-center', {text: 'Example notification text.', pos: 'bottom-center'})
add_notification('.bottom-right', {text: 'Example notification text.', pos: 'bottom-right'})

// Action Button

add_notification('.no-action', {showAction: false})

// Action Text

add_notification('.action-text', {actionText: 'Thanks!'})
    
// Text Color

add_notification('.text-color', {actionTextColor: '#e2a03f'})

// Click Callback

add_notification('.click-callback', {
    text: 'Custom callback when action button is clicked.',
    width: 'auto',
    onActionClick: function(element) {
        document.querySelector(`.${element.classList[0]}`).addEventListener('click', function() {
            Snackbar.show({text: 'Thanks for clicking.',});
        })
    }
})

// Duration

add_notification('.duration', { text: 'Duration set to 5s', duration: 5000,})


// Custom Background

add_notification('.snackbar-bg-primary', { 
    text: 'Primary',
    actionTextColor: '#fff',
    backgroundColor: '#4361ee'
})

add_notification('.snackbar-bg-info', { 
    text: 'info',
    actionTextColor: '#fff',
    backgroundColor: '#2196f3'
})

add_notification('.snackbar-bg-success', { 
    text: 'Success',
    actionTextColor: '#fff',
    backgroundColor: '#00ab55'
})

add_notification('.snackbar-bg-warning', { 
    text: 'Warning',
    actionTextColor: '#fff',
    backgroundColor: '#e2a03f'
})

add_notification('.snackbar-bg-danger', { 
    text: 'Danger',
    actionTextColor: '#fff',
    backgroundColor: '#e7515a'
})

add_notification('.snackbar-bg-secondary', { 
    text: 'Secondary',
    actionTextColor: '#fff',
    backgroundColor: '#805dca'
})

add_notification('.snackbar-bg-dark', { 
    text: 'Dark',
    actionTextColor: '#fff',
    backgroundColor: '#3b3f5c'
})