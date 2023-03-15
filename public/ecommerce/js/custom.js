var customer_id = $('#hid_customer_id').val();
var searchObject = localStorage.getItem("search_history");
    searchObject = searchObject ? JSON.parse(searchObject) : [];
var checked_address = 0;
let selected_address = 0;
var oldbehalf_company = $('#reg_behalf_company').val();
var bank_country = $('#hid_bank_country').val();


$(document).ready(function () {
    // Remove summernote style
    $('.cms_text span').attr('style', '');
    $footerHeight = $(".footer").height();
    $('body').css('margin-bottom', $footerHeight + 20);
    $navHeight = $("#full_width_nav .navbar").outerHeight();
    $alertHeight = $(".navbar_alert").outerHeight();
    $navSubmenu = $(".navbar_submenu").outerHeight();
    $openSide = $(".open_side").outerHeight();
    $(".navbar_alert, .navbar_submenu").css('margin-top', $navHeight);
    $(".no_submenu_container").css('margin-top', $navHeight); //for web marketplace
    $(".sell_banner, .no_banner_container").css('margin-top', $navHeight + $navSubmenu);

    var docWidth = $(document).width();

    if (docWidth <= 575.98) {
        var linkTitle = document.getElementsByClassName('slick_link_title');
        for (var i = 0; i < linkTitle.length; i++) {
            linkTitle[i].innerHTML = "See all <i class='demo-icon icon-angle-right font_20 text_text'></i>";
        }
    }

    if (docWidth <= 991.98) {
        $('#full_width_nav').css('display', 'none');
        $('#mobile_nav').css('display', 'block');

        $navHeightMobile = $("#mobile_nav").outerHeight();
        $(".navbar_alert, .navbar_submenu").css('margin-top', $navHeightMobile);
        $(".side_menu").css('margin-top', $navHeightMobile);
        $(".open_side").css('top', $navHeightMobile);
        $('.no_submenu_container').css('margin-top', 0);
        // $(".carousel_mainbanner").css('margin-top', $alertHeight);
        $('.no_banner_container').css('margin-top', $navSubmenu);
        $('.sell_banner').css('margin-top', $navSubmenu);
        $(".profile_right").css('margin-top', $openSide);
    }

    $('#sellerAgreementDetailModal.modal').on('shown.bs.modal', function (e) {
        var numNavItem = $('#seller_agreement_tabs .nav-item').length;
        var navTabContainer = $("#seller_agreement_tabs").width();
        var navTabsWidth = navTabContainer / numNavItem;
        $('#seller_agreement_tabs .nav-item').width(navTabsWidth - 1);
        var firstChildNav = document.getElementById("seller_agreement_tabs").firstElementChild
        console.log(firstChildNav);
        var firstChildNavLink = firstChildNav.firstElementChild;
        firstChildNavLink.classList.add("active");

        var tabContent = document.getElementById('seller_tabs_content').firstElementChild
        tabContent.classList.add("active");
    });


    $(".dropdown").hover(
        function () {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).fadeIn("10");
            $(this).toggleClass('open');
        },
        function () {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).fadeOut("10");
            $(this).toggleClass('open');
        }
    );

    // Search Form
    $('[data-toggle=search-form]').click(function () {
        $('.search-form-wrapper, .search-form-mobile').toggleClass('open');
        $('.search-form-wrapper .search, .search-form-mobile .search').focus();
        $('html').toggleClass('search-form-open');
    });
    $('[data-toggle=search-form-close]').click(function () {
        $('.search-form-wrapper, .search-form-mobile').removeClass('open');
        $('html').removeClass('search-form-open');
    });
    $('.search-form-wrapper .search, .search-form-mobile .search').keypress(function (event) {
        if ($(this).val() == "Search") $(this).val("");
        // alert('hello')
    });

    $("#txtsearch").bind("focus", function() {
        if(searchObject.length > 0) {
            var appendspan = '';
            for(var i= searchObject.length - 1; i >= 0; i--)
            {
                appendspan += '<span>'+searchObject[i]+'<span>';
                $( "#txtsearch" ).after( appendspan );
            }
        }
    });

    $('#search-word').click(function (event) {
        event.preventDefault();
        var search_text = $('#txtsearch').val();
        if(searchObject.length < 5){
            searchObject.push(search_text);
            localStorage.setItem("search_history",  JSON.stringify(searchObject));
        }else{
            searchObject.splice(0,1);
            searchObject.push(search_text);
            localStorage.setItem("search_history",JSON.stringify(searchObject));
        }
        console.log("newArray contents = "+ searchObject);
        $('#search_form').submit();
    });

    $('#search-close').click(function (event) {
        $('.search-form-wrapper, .search-form-mobile').removeClass('open');
        $('html').removeClass('search-form-open');
    });

    // Favourite
    // $('.fav img').click(function() {
    //     // console.log($(this).attr('src'));
    //     $(this).attr('src', '{!! asset("ecommerce/icons/fav_active.png ") !!}');
    // });

    // Carousel tab switch
    var slickObjLg = {
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        mobileFirst: true,
        responsive: [{
                breakpoint: 767,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true
                }
            },
            {
                breakpoint: 300,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
        ]
    }

    $('.auction_catalogue, .bid_ending_soon, .new_arrival, .recentAuction, .hightLights, .sold').slick(slickObjLg);

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href") // activated tab

        if (target == '#bid-recently-sold') {
            $('.bid_recently_sold').slick(slickObjLg);
        }

        if (target == '#bid-ending-soon') {
            $('.bid_ending_soon').slick(slickObjLg);
        }

        if (target == '#new-arrival') {
            $('.new_arrival').slick(slickObjLg);
        }

        if (target == '#clearrance') {
            $('.clearrance').slick(slickObjLg);
        }

        if (target == '#just-listed') {
            $('.recentAuction').slick(slickObjLg);
        }

        if (target == '#sale-highlight') {
            $('.highLight').slick(slickObjLg);
        }

        if (target == '#buyNow') {
            $('.buyNow').slick(slickObjLg);
        }

        if (target == '#sold') {
            $('.sold').slick(slickObjLg);
        }
    });

    $('.what_we_sell').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 4,
        mobileFirst: true,
        responsive: [{
                breakpoint: 767.98,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    infinite: true
                }
            },
            {
                breakpoint: 300,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
        ]
    });

    $('.itemDetail').slick({
        infinite: true,
        slidesToShow: 7,
        slidesToScroll: 7,
        mobileFirst: true,
        responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 5,
                    infinite: true
                }
            },
            {
                breakpoint: 300,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
        ]

    });


    // Item Detail
    /* $(document).on('click', ".img_thumb", function () {
        var path = $(this).attr('image_path');
        var mainImage = document.getElementById('mainImg');
        $('.active').removeClass('active'); // only one element has active class, so trigger by active
        $(this).addClass('active');
        mainImage.src = path;
    });  */

    // Shopping cart noti
    var found = '';
    var existing_items = JSON.parse(window.localStorage.getItem(customer_id))

    if(existing_items != null){
        found = existing_items.items.length;
    }

    if(found > 0) {
        $('#shopping_noti, #shopping_noti_mobile').text(found);
        if (!$("#shopping_noti, #shopping_noti_mobile").hasClass("shopping_noti"))
        {
            $('#shopping_noti, #shopping_noti_mobile').addClass("shopping_noti");
        }
    }

    var noti_style = document.getElementById('shopping_noti');
    var noti_mobile_style = document.getElementById('shopping_noti_mobile');
    if (typeof (noti_style) != 'undefined' && noti_style != null) {
        var noti_count = document.getElementById('shopping_noti').innerHTML;
        if (noti_count != "") {
            noti_style.classList.add('shopping_noti');
        } else {
            noti_style.classList.remove('shopping_noti');
        }
    }
    if (typeof (noti_mobile_style) != 'undefined' && noti_mobile_style != null) {
        var noti_count = document.getElementById('shopping_noti_mobile').innerHTML;
        if (noti_count != "") {
            noti_style.classList.add('shopping_noti');
        } else {
            noti_style.classList.remove('shopping_noti');
        }
    }

    var oldreg_credit_card = $('#hid_old_reg_credit').val();

    if (oldreg_credit_card == '1') {
        $('.captchblog').show();
    } else {
        $('.captchblog').hide();
    }

    // Register Form
    $('input[type=radio][name=reg_credit_card]').change(function () {
        if (this.value == '1') {
            $('.captchblog').show();
        } else {
            $('.captchblog').hide();
        }
    });

    $('input[type=radio][name=reg_behalf_company]').change(function() {
        if (this.value == '1') {
            $('.companyblog').show();
        }
        else {
            $('.companyblog').hide();
        }
        $('.sg_uen_number_input').val('');
        $('.company_name_input').val('');
    });

    $('input[type=radio][name=reg_gst_sg]').change(function () {
        if (this.value == 1) {
            $('.gstContent').show();
        }
        else {
            $('.gstContent').hide();
        }
        $('.gst_number_input').val('');
    });

    var radioValue = $("input[type=radio][name=communication]:checked").val();
    if (radioValue == 'option1') {
        lastCheck();
    }

    $('input[type=radio][name=communication]').change(function() {
        var checkVal = $('#preference input[type = checkbox]');
        if(this.value == 'option1') {
            checkVal.prop('checked', true);
            lastCheck();
        }else {
            checkVal.prop('checked', false);
            checkVal.change(function () {
                $(this).prop('checked', false)
            })
        }
    });

    $('input[type=radio][name=chk_ship]').change(function () {
        if (this.value == 'yes') {
            $('#shipping_section').css('display', 'block');
        } else {
            $('#shipping_section').css('display', 'none');
        }
    });

    if(bank_country == 'singapore') {
        $('#edit_other_country').css('display', 'none');
        $('#bank_country').css('display', 'none');
        $('#show_other_country').css('display', 'none');
        $('#show_country_name').css('display', 'none');
    }else{
        $('#edit_other_country').css('display', 'block');
        $('#bank_country').css('display', 'block');
        $('#show_other_country').css('display', 'block');
        $('#show_country_name').css('display', 'block');
    }
    $('input[type=radio][name=chk_country]').change(function () {
        if (this.value == 'singapore') {
            $('#edit_other_country').css('display', 'none');
            $('#bank_country').css('display', 'none');
        } else {
            $('#edit_other_country').css('display', 'block');
            $('#bank_country').css('display', 'block');
        }
    });

    $("#upload_image").change(function() {
      readURL(this);
      $('#btn_image_save').show();
    });

    $("#upload_image_mobile").change(function () {
      readURL(this);
      $('#btn_image_save_mobile').css('display', 'block');
    });

    // Add New Form
    $('#add_new_customer').click(function() {
        click_add_new = 1;
        // $('#btn_addnew_cancel').css('display', 'block');
        $('input[name="is_primary"]').each(function() {
            this.checked = false;
        });
        $('#new_address').css('display', 'block');
        $('#add_new_customer').hide();
    });

    $('#add_new_c_address').click(function() {
        $('#new_c_address').css('display', 'block');
        $('#add_new_c_address').hide();
    });

    $('#btn_addnew_cancel').click(function() {
        click_add_new = 0;
    });

    $('#add_new_card').click(function() {
        $('#new_card').css('display', 'block');
    });

    $('#add_new_bank').click(function () {
        $('#new_bank').css('display', 'block');
    });

    const rbs = document.querySelectorAll('input[name="is_primary"]');
    for (const rb of rbs) {
        if (rb.checked) {
            checked_address = rb.value;
            selected_address = rb.value;
            break;
        }
    }

    $('input:radio[name="is_primary"]').change(
        function(){
        if ($(this).is(':checked')) {
            checked_address = $(this).val();
        }
    });
});

// Form Show Hide
function showEditProfileForm() {
    $('.manage_personal').hide();
    $('.update_personal').show();
}
function cancelProfileData() {
    $('.update_personal').hide();
    $('.manage_personal').show();
}

function showEditCompanyForm() {
    $('.manage_company').hide();
    $('.update_company').show();
}
function cancelCompanyData() {
    $('.update_company').hide();
    $('.manage_company').show();
}

function showEditGstForm() {
    $('.manage_gst').hide();
    $('.update_gst').show();
}
function cancelGstData() {
    $('.update_gst').hide();
    $('.manage_gst').show();
}

function showEditBankForm() {
    $('.manage_bank').hide();
    $('.update_bank').show();
}
function cancelBankData() {
    $('.update_bank').hide();
    $('.manage_bank').show();
}

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#profile_photo, #profile_photo_mobile').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

function lastCheck() {
    $('#preference input[type = checkbox]').change(function () {
        var ischecked = $(this).is(':checked');
        if (!ischecked) {
            var countCheckedCheckboxes = $('#preference input[type = checkbox]').filter(':checked').length;
            if (countCheckedCheckboxes == 0) {
                console.log($(this).prop('checked', true));
            }
        }
    })
}

function show_edit_form(id) {
    $('.edit_address').hide();
    $('#add_new_customer').hide();
    $('.edit_correspondence_address').hide();
    $('#new_c_address').hide();
    $('#edit_address_' + id).show();
}

function show_correspondence_edit_form() {
    $('.edit_address').hide();
    $('#add_new_customer').hide();
    $('#new_c_address').hide();
    $('#edit_correspondence_address').show();
}
