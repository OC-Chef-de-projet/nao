/* ============================================================
 NAO <Nos amis les oiseaux> Javascript functions
 ==================================================================*/
(function($){

    $(function(){

        /* ==================================================
         IOS COMPATIBILITY
         ===========================================================*/
        /*var _iOSDevice = !!navigator.platform.match(/iPhone|iPod|iPad/);*/

        /**
         * safari on iOS >= 9 ignore user-scalable meta
         */
        document.documentElement.addEventListener('touchstart', function (event) {
            if (event.touches.length > 1) { event.preventDefault(); }
        }, false);


        /* ==================================================
         NAVIGATION
         ===========================================================*/
        var buttonCollapse      = $('.button-collapse');
        var scrollTo            = $('.scroll-to');
        var scrollTop           = $('#scroll-top');
        var header              = $('header');
        var navbar              = $('header nav');
        var subSearch           = $('#sub-search');
        var dropdownControl     = $('.dropdown-button');
        var navLogo             = $('#nav-logo');
        var navSmallTitle       = $('#nav-small-title');
        var tabs                = $('header ul.tabs');
        var HeaderMainTitle     = $('#header-main-title');
        var PageTitle           = $('.page-heading div.heading');
        var to_top_offset       = 5;

        _init_navigation();

        /**
         * Let's init navigation system
         * @private
         */
        function _init_navigation(){
            // side menu
            buttonCollapse.sideNav();

            // Load navigation style
            switchNavigationStyle();

            // Dropdown
            dropdownControl.dropdown({
                constrainWidth: false,
                belowOrigin: true
            });
        }

        /**
         * Go to anchor, he will be apply to all 'scroll-to" class
         */
        scrollTo.on('click', function(event) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: $( $(this).attr('href') ).offset().top - 70
            }, 700)
        });

        /**
         * the navigation style changes when the user scrolls through the page
         */
        $(window).scroll(function(){
            switchNavigationStyle();
            return false;
        });

        /**
         * Switch heading and navigation style
         */
        function switchNavigationStyle(){

            var scroll = $(this).scrollTop();

            // Only for collaspse header
            if(header.hasClass('collapse')){

                if(HeaderMainTitle.length){
                    var positionMainTitleToTop = HeaderMainTitle.offset().top - $(window).scrollTop();
                }else{
                    var positionMainTitleToTop = tabs.offset().top - $(window).scrollTop();
                }
                
                /**
                 * When the title is over the screen
                 * We can display sub title in the navbar and hide main logo for mobile
                 */
                if(positionMainTitleToTop <= 0){
                    navLogo.addClass('activated');
                    navSmallTitle.addClass('activated');
                    PageTitle.addClass('activated');
                }else{
                    navLogo.removeClass('activated');
                    navSmallTitle.removeClass('activated');
                    PageTitle.removeClass('activated');
                }
            }

            /**
             * To do for scroll page
             */
            if(scroll > to_top_offset ){
                navbar.addClass('sticky');
                if(header.hasClass('collapse')){
                    header.addClass('sticky');
                }
                if(header.hasClass('classic')){
                    navLogo.addClass('activated');
                    navSmallTitle.addClass('activated');
                }

            }else{
                navbar.removeClass('sticky');
                if(header.hasClass('collapse')){
                    header.removeClass('sticky');
                }
                if(header.hasClass('classic')){
                    navLogo.removeClass('activated');
                    navSmallTitle.removeClass('activated');
                }
            }
        }

        /**
         * Prevents search dropdown from disappearing
         */
        subSearch.on('click', function(event){
            event.stopPropagation();
        });

        /**
         * Scroll to the top of page
         */
        scrollTop.on('click', function(event){
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop : 0
            },700);
        });

        /**
         * Push tabs in the top of the page after scrolling to this element
         */
        if(tabs.length){
            tabs.pushpin({
                top: tabs.offset().top,
                bottom: 10000,
                offset: 63
            });
        }

        /* ==================================================
         SOCIAL SHARING
         ===========================================================*/
        var sharing         = $('.sharing');
        var sharingBox      = $('#sharingBox');
        var socialLink      = $('.share-link');
        var offsetToElement = 5;

        /**
         * Control the display to the social sharing box
         * it will be show below parent element
         */
        sharing.on('click', function(event){
            event.preventDefault();
            event.stopPropagation();

            var origin  = $(this);
            var data    = origin.data();

            // inject social url
            $('#sharingBox li a').each(function(){
                $(this).data('link', data.link);
            });

            if(data.role === 'dropdown'){

                // keep socialBox inside fixed navbar
                origin.after(sharingBox);
                var leftPosition    = $(window).width() - 160;
                var topPosition     = navbar.height();

                // Close materialize Dropdown
                dropdownControl.dropdown('close');

            }else{
                header.before(sharingBox);
                var leftPosition    = origin.offset().left - offsetToElement;
                var topPosition     = origin.offset().top + offsetToElement;
            }
            sharingBox.hide().css({
                top : topPosition + 'px',
                left: leftPosition + 'px'
            }).slideDown('fast');
        });

        /**
         * Hide the box if focus page
         */
        $(document).on('click', sharingBox ,function() {
            sharingBox.fadeOut();
        });

        /**
         * Control the display to the social sharing box
         * after calling ajax function to delagate of parent container
         */
        $('#eltlist').on('click', '.sharing', function(e){
            event.preventDefault();
            event.stopPropagation();

            var origin  = $(this);
            var data    = origin.data();

            // inject social url
            $('#sharingBox li a').each(function(){
                $(this).data('link', data.link);
            });

            if(data.role === 'dropdown'){

                // keep socialBox inside fixed navbar
                origin.after(sharingBox);
                var leftPosition    = $(window).width() - 160;
                var topPosition     = navbar.height();

                // Close materialize Dropdown
                dropdownControl.dropdown('close');

            }else{
                header.before(sharingBox);
                var leftPosition    = origin.offset().left - offsetToElement;
                var topPosition     = origin.offset().top + offsetToElement;
            }
            sharingBox.hide().css({
                top : topPosition + 'px',
                left: leftPosition + 'px'
            }).slideDown('fast');
        });

        /**
         * displays a social sharing window depending on the provider and input parameters
         */
        socialLink.on('click', function(event){
            event.preventDefault();
            var origin   = $(this);
            var data    = origin.data();

            switch(data.social) {
                case 'facebook':
                    var href    = 'https://www.facebook.com/sharer.php?u='+ data.link;
                    var height  = 400;
                    var width   = 700;
                    break;
                case 'google':
                    var href    = 'https://plus.google.com/share?url=' + data.link + '&hl=fr';
                    var height  = 650;
                    var width   = 400;
                    break;
                case 'twitter':
                    var href= 'https://twitter.com/share?url='+ data.link + '&text=' + '&via=Nos amis les oiseaux';
                    var height  = 300;
                    var width   = 700;
                    break;
                default:
                    console.log('Provider not found.')
            }

            window.open(href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height='+ height +',width='+ width +'');;
            return false;
        });

        /* ==================================================
         NEWSLETTER FORM
         ===========================================================*/
        var globalNewsForm      = $('#global_form_newsletter');
        var newsForm            = $('#form_newsletter');

        globalNewsForm.validate({
            rules: {
                ng_email: {
                    required: true,
                    email: true,
                }
            },
            errorElement : 'div',
            errorPlacement: function(error, element) {
                element.removeClass('valid').addClass('invalid');
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var ajxUrl  = $(form).attr("action");
                var data    =  $(form).serialize();

                $.post( ajxUrl, data, function( data ) {
                    var element = $(form).find('input[name=ng_email]');
                    if(data.status === false){
                        element.removeClass('valid').addClass('invalid');
                        element.next('div.error').html(data.message).show();
                    }else{
                        Materialize.toast(data.message, 4000);
                        $(form)[0].reset();
                        $(form).find('label').removeClass('active');
                    }
                });
            }
        });

        newsForm.validate({
            rules: {
                ng_email: {
                    required: true,
                    email: true,
                }
            },
            errorElement : 'div',
            errorPlacement: function(error, element) {
                element.removeClass('valid').addClass('invalid');
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var ajxUrl  = $(form).attr("action");
                var data    =  $(form).serialize();

                $.post( ajxUrl, data, function( data ) {
                    var element = $(form).find('input[name=ng_email]');
                    if(data.status === false){
                        element.removeClass('valid').addClass('invalid');
                        element.next('div.error').html(data.message).show();
                    }else{
                        Materialize.toast(data.message, 4000);
                        $(form)[0].reset();
                        $(form).find('label').removeClass('active');
                    }
                });
            }
        });

        /* ====================================
         COLLAPSIBLE ACCORDION
         =============================================*/
        var collapsibleHeader   = $('.collapsible-header');

        $('.collapsible').collapsible({
            accordion: false,
            onOpen: function(el) {
                collapsibleHeader.find('i').html('keyboard_arrow_right');
                el.find('i').html('keyboard_arrow_down');
            }, // Callback for Collapsible open
            onClose: function(el) {
                el.find('i').html('keyboard_arrow_right');
            } // Callback for Collapsible close
        });

        /* ==================================================
         FEEDBACK FORM
         ===========================================================*/
        var feedbackForm      = $('#feedback_form');

         feedbackForm.validate({
            rules: {
                'feedback[object]': { minlength: 2 },
                'feedback[message]': { minlength: 2 }
            },
            errorElement : 'div',
            errorPlacement: function(error, element) {
                //element.removeClass('valid').addClass('invalid');
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });

        /* ==================================================
         REGSITER FORM
         ===========================================================*/
        var registerForm      = $('#register_form');

        registerForm.validate({
            rules: {
                'register[plainPassword][first]': { minlength: 2 },
                'register[plainPassword][second]': { equalTo: "#register_plainPassword_first" }
            },
            errorElement : 'div',
            errorPlacement: function(error, element) {
                //element.removeClass('valid').addClass('invalid');
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });

        /* ==================================================
         REGSITER FORM
         ===========================================================*/
        var loginForm      = $('#login_form');

        loginForm.validate({
            errorElement : 'div',
            errorPlacement: function(error, element) {
                //element.removeClass('valid').addClass('invalid');
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });

        /* ==================================================
         RESET PASSWORD FORM
         ===========================================================*/
        var resetPasswordForm      = $('#reset_password_form');

        resetPasswordForm.validate({
            errorElement : 'div',
            errorPlacement: function(error, element) {
                //element.removeClass('valid').addClass('invalid');
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });

        /* ==================================================
         GLOBAL DIALOGS
         ===========================================================*/
         if(typeof toastMsg !== 'undefined'){
             Materialize.toast(toastMsg, 8000);
             toastMsg = undefined;
         }

        /* ==================================================
         COMMENT FORM
         ===========================================================*/
        var CommentForm         = $('#form_comment');
        var commentInput        = CommentForm.find('textarea');
        var countChars          = $('#count_chars');
        var noComment           = $('#no-comment');
        var commentList         = $('#cmt-list');

        commentInput.on('keyup', function(event){
            countChars.html(commentInput.val().length);
        });

        CommentForm.validate({
            errorElement : 'div',
            errorPlacement: function(error, element) {
                element.removeClass('valid').addClass('invalid');
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var ajxUrl  = $(form).attr("action");
                var data    =  $(form).serialize();

                $.post( ajxUrl, data, function( data ) {
                    var element = $(form).find('textarea');
                    if(data.status === false){
                        element.removeClass('valid').addClass('invalid');
                        element.next('div.error').html(data.message).show();
                    }else{
                        if(data.validate === false){
                            Materialize.toast(data.message, 4000);
                        }else{
                            noComment.remove();
                            commentList.prepend(data.message);
                        }
                        countChars.html(0);
                        $(form)[0].reset();
                        $(form).find('label').removeClass('active');
                    }
                });
            }
        });

        /* ==================================================
         PRRFILLING TEXT INPUTS
         ===========================================================*/
        Materialize.updateTextFields();

        /* ==================================================
         MODAL
         ===========================================================*/
        $('.modal').modal();

        /* ==================================================
         COOKIE POLICY
         ===========================================================*/
        var cookieContent           = $('section.cookie-law');
        var acceptCookieAction      = $('#accept_cookie_policy');
        
        acceptCookieAction.on('click', function (event){
           event.preventDefault();
            cookieContent.remove();
            var date = new Date();
            date.setTime(date.getTime()+(7*24*60*60*1000));
            document.cookie = "nao-cookies-policy=1;expires="+date.toGMTString();
        });

        /* ==================================================
         SELECT
         ===========================================================*/
        $('select').material_select();

        /* ==================================================
         DATEPICKER
         ===========================================================*/
        $('.datepicker').pickadate({
            monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthsShort: ['Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juill', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
            weekdaysFull: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            weekdaysLetter: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            format: 'dd/mm/yyyy',
            selectMonths: true,
            selectYears: 2,
            today: 'Aujourd\'hui',
            clear: 'Supprimer',
            close: 'Ok',
            firstDay: 1,
            closeOnSelect: false,
            max: new Date()
        })

    });
})(jQuery);
