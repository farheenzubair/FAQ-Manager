jQuery(document).ready(function($) 
{
    // Accordion functionality for showing/hiding FAQ answers
    $('.faq-accordion h4').click(function() 
    {
        $(this).next('div').slideToggle();  
        $(this).toggleClass('active');      
    });

    // Handle like and dislike button clicks
    $('.like-button, .dislike-button').on('click', function() 
    {
        var button = $(this);         
        var faqId = button.data('faq-id'); 
        var action = button.hasClass('like-button') ? 'like' : 'dislike'; 

        // Send the AJAX request
        $.ajax({
            url: ajaxurl,  
            type: 'POST',  
            data: {
                action: 'update_faq_reaction',  
                faq_id: faqId,                
                reaction: action            
            },
            success: function(response) 
            {
                if (response.success) 
                    {
                    // Update the button text with the new count
                    var newCount = response.data.newCount;
                    if (action === 'like') {
                        button.text('👍 ' + newCount);  
                    } else {
                        button.text('👎 ' + newCount);  
                    }
                } else {
                    alert('Something went wrong. Please try again.');
                }
            },
            error: function() 
            {
                alert('Error processing your request. Please try again.');
            }
        });
    });
});
