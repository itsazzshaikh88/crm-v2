"use strict";
var KTAppEcommerceSaveProduct = (function () {
    const e = () => {
        $("#kt_ecommerce_add_product_options").repeater({
            initEmpty: !1,
            defaultValues: { "text-input": "foo" },
            show: function () {
                $(this).slideDown(), t();
            },
            hide: function (e) {
                $(this).slideUp(e);
            },
        });
    },
        t = () => {
            document
                .querySelectorAll(
                    '[data-kt-ecommerce-catalog-add-product="product_option"]'
                )
                .forEach((e) => {
                    $(e).hasClass("select2-hidden-accessible") ||
                        $(e).select2({ minimumResultsForSearch: -1 });
                });
        };
    return {
        init: function () {
            var o, a;
            [
                "#kt_ecommerce_add_product_meta_description",
            ].forEach((e) => {
                let t = document.querySelector(e);
                t &&
                    (t = new Quill(e, {
                        modules: {
                            toolbar: [
                                [{ header: [1, 2, !1] }],
                                ["bold", "italic", "underline"],
                                ["image", "code-block"],
                            ],
                        },
                        placeholder: "Type your text here...",
                        theme: "snow",
                    }));
            }),

                [
                    "#kt_ecommerce_add_product_category",
                    "#kt_ecommerce_add_product_tags",
                ].forEach((e) => {
                    const t = document.querySelector(e);
                    t &&
                        new Tagify(t, {
                            whitelist: [
                                "new",
                                "trending",
                                "sale",
                                "discounted",
                                "selling fast",
                                "last 10",
                            ],
                            dropdown: {
                                maxItems: 20,
                                classname: "tagify__inline__suggestions",
                                enabled: 0,
                                closeOnSelect: !1,
                            },
                        });
                }),
                (o = document.querySelector(
                    "#kt_ecommerce_add_product_discount_slider"
                )),
                (a = document.querySelector(
                    "#kt_ecommerce_add_product_discount_label"
                )),
                noUiSlider.create(o, {
                    start: [0],
                    connect: !0,
                    range: { min: 1, max: 100 },
                }),
                o.noUiSlider.on("update", function (e, t) {
                    (a.innerHTML = Math.round(e[t])),
                        t && (a.innerHTML = Math.round(e[t]));

                    document.getElementById("DISCOUNT_PERCENTAGE").value = Math.round(e[t]) || 0;
                }),
                e(),
                new Dropzone("#kt_ecommerce_add_product_media", {
                    url: "https://keenthemes.com/scripts/void.php",
                    paramName: "file",
                    maxFiles: 10,
                    maxFilesize: 10,
                    addRemoveLinks: !0,
                    accept: function (e, t) {
                        "wow.jpg" == e.name ? t("Naha, you don't.") : t();
                    },
                }),
                t(),
                (() => {
                    const e = document.getElementById("kt_ecommerce_add_product_status"),
                        t = document.getElementById(
                            "kt_ecommerce_add_product_status_select"
                        ),
                        o = ["bg-success", "bg-warning", "bg-danger"];
                    $(t).on("change", function (t) {
                        switch (t.target.value) {
                            case "published":
                                e.classList.remove(...o), e.classList.add("bg-success"), c();
                                break;
                            case "scheduled":
                                e.classList.remove(...o), e.classList.add("bg-warning"), d();
                                break;
                            case "inactive":
                                e.classList.remove(...o), e.classList.add("bg-danger"), c();
                                break;
                            case "draft":
                                e.classList.remove(...o), e.classList.add("bg-primary"), c();
                        }
                    });
                    const a = document.getElementById(
                        "kt_ecommerce_add_product_status_datepicker"
                    );
                    $("#kt_ecommerce_add_product_status_datepicker").flatpickr({
                        enableTime: !0,
                        dateFormat: "Y-m-d H:i",
                    });
                    const d = () => {
                        a.parentNode.classList.remove("d-none");
                    },
                        c = () => {
                            a.parentNode.classList.add("d-none");
                        };
                })(),
                (() => {
                    const e = document.querySelectorAll('[name="method"][type="radio"]'),
                        t = document.querySelector(
                            '[data-kt-ecommerce-catalog-add-category="auto-options"]'
                        );
                    e.forEach((e) => {
                        e.addEventListener("change", (e) => {
                            "1" === e.target.value
                                ? t.classList.remove("d-none")
                                : t.classList.add("d-none");
                        });
                    });
                })(),
                (() => {
                    const e = document.querySelectorAll('.discount_option'),
                        t = document.getElementById(
                            "kt_ecommerce_add_product_discount_percentage"
                        ),
                        o = document.getElementById(
                            "kt_ecommerce_add_product_discount_fixed"
                        );
                    e.forEach((e) => {
                        e.addEventListener("change", (e) => {
                            switch (e.target.value) {
                                case "2":
                                    t.classList.remove("d-none"), o.classList.add("d-none");
                                    break;
                                case "3":
                                    t.classList.add("d-none"), o.classList.remove("d-none");
                                    break;
                                default:
                                    t.classList.add("d-none"), o.classList.add("d-none");
                            }
                        });
                    });
                })();
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceSaveProduct.init();
});
