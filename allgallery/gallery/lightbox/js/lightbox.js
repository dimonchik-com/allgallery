LightboxOptions = Object.extend({
    fileLoadingImage: url_home+"/plugins/content/joomthumbnail/gallery/lightbox/images/loading.gif",
    fileBottomNavCloseImage: url_home+"/plugins/content/joomthumbnail/gallery/lightbox/images/closelabel.gif",
    overlayOpacity: 0.8,
    animate: true,
    resizeSpeed: 7,
    borderSize: 10,
    labelImage: "Image",
    labelOf: "of"
},
window.LightboxOptions || {});
var Lightbox = Class.create();
Lightbox.prototype = {
    imageArray: [],
    activeImage: undefined,
    initialize: function () {
        this.updateImageList();
        this.keyboardAction = this.keyboardAction.bindAsEventListener(this);
        if (LightboxOptions.resizeSpeed > 10) {
            LightboxOptions.resizeSpeed = 10;
        }
        if (LightboxOptions.resizeSpeed < 1) {
            LightboxOptions.resizeSpeed = 1;
        }
        this.resizeDuration = LightboxOptions.animate ? ((11 - LightboxOptions.resizeSpeed) * 0.15) : 0;
        this.overlayDuration = LightboxOptions.animate ? 0.2 : 0;
        var size = (LightboxOptions.animate ? 250 : 1) + "px";
        var objBody = $$("body")[0];
        objBody.appendChild(Builder.node("div", {
            id: "overlay"
        }));
        objBody.appendChild(Builder.node("div", {
            id: "lightbox"
        },
        [Builder.node("div", {
            id: "outerImageContainer"
        },
        Builder.node("div", {
            id: "imageContainer"
        },
        [Builder.node("img", {
            id: "lightboxImage"
        }), Builder.node("div", {
            id: "hoverNav"
        },
        [Builder.node("a", {
            id: "prevLink",
            href: "#"
        }), Builder.node("a", {
            id: "nextLink",
            href: "#"
        })]), Builder.node("div", {
            id: "loading"
        },
        Builder.node("a", {
            id: "loadingLink",
            href: "#"
        },
        Builder.node("img", {
            src: LightboxOptions.fileLoadingImage
        })))])), Builder.node("div", {
            id: "imageDataContainer"
        },
        Builder.node("div", {
            id: "imageData"
        },
        [Builder.node("div", {
            id: "imageDetails"
        },
        [Builder.node("span", {
            id: "caption"
        }), Builder.node("span", {
            id: "numberDisplay"
        })]), Builder.node("div", {
            id: "bottomNav"
        },
        Builder.node("a", {
            id: "bottomNavClose",
            href: "#"
        },
        Builder.node("img", {
            src: LightboxOptions.fileBottomNavCloseImage
        })))]))]));
        $("overlay").hide().observe("click", (function () {
            this.end();
        }).bind(this));
        $("lightbox").hide().observe("click", (function (event) {
            if (event.element().id == "lightbox") {
                this.end();
            }
        }).bind(this));
        $("outerImageContainer").setStyle({
            width: size,
            height: size
        });
        $("prevLink").observe("click", (function (event) {
            event.stop();
            this.changeImage(this.activeImage - 1);
        }).bindAsEventListener(this));
        $("nextLink").observe("click", (function (event) {
            event.stop();
            this.changeImage(this.activeImage + 1);
        }).bindAsEventListener(this));
        $("loadingLink").observe("click", (function (event) {
            event.stop();
            this.end();
        }).bind(this));
        $("bottomNavClose").observe("click", (function (event) {
            event.stop();
            this.end();
        }).bind(this));
        var th = this;
        (function () {
            var ids = "overlay lightbox outerImageContainer imageContainer lightboxImage hoverNav prevLink nextLink loading loadingLink " + "imageDataContainer imageData imageDetails caption numberDisplay bottomNav bottomNavClose";
            $w(ids).each(function (id) {
                th[id] = $(id);
            });
        }).defer();
    },
    updateImageList: function () {
        this.updateImageList = Prototype.emptyFunction;
        document.observe("click", (function (event) {
            var target = event.findElement("a[rel^=lightbox]") || event.findElement("area[rel^=lightbox]");
            if (target) {
                event.stop();
                this.start(target);
            }
        }).bind(this));
    },
    start: function (imageLink) {
        $$("select", "object", "embed").each(function (node) {
            node.style.visibility = "hidden";
        });
        var arrayPageSize = this.getPageSize();
        $("overlay").setStyle({
            width: arrayPageSize[0] + "px",
            height: arrayPageSize[1] + "px"
        });
        new Effect.Appear(this.overlay, {
            duration: this.overlayDuration,
            from: 0,
            to: LightboxOptions.overlayOpacity
        });
        this.imageArray = [];
        var imageNum = 0;
        if ((imageLink.rel == "lightbox")) {
            this.imageArray.push([imageLink.href, imageLink.title]);
        } else {
            this.imageArray = $$(imageLink.tagName + '[href][rel="' + imageLink.rel + '"]').collect(function (anchor) {
                return [anchor.href, anchor.title];
            }).uniq();
            while (this.imageArray[imageNum][0] != imageLink.href) {
                imageNum++;
            }
        }
        var arrayPageScroll = document.viewport.getScrollOffsets();
        var lightboxTop = arrayPageScroll[1] + (document.viewport.getHeight() / 10);
        var lightboxLeft = arrayPageScroll[0];
        this.lightbox.setStyle({
            top: lightboxTop + "px",
            left: lightboxLeft + "px"
        }).show();
        this.changeImage(imageNum);
    },
    changeImage: function (imageNum) {
        this.activeImage = imageNum;
        if (LightboxOptions.animate) {
            this.loading.show();
        }
        this.lightboxImage.hide();
        this.hoverNav.hide();
        this.prevLink.hide();
        this.nextLink.hide();
        this.imageDataContainer.setStyle({
            opacity: 0.0001
        });
        this.numberDisplay.hide();
        var imgPreloader = new Image();
        imgPreloader.onload = (function () {
            this.lightboxImage.src = this.imageArray[this.activeImage][0];
            this.resizeImageContainer(imgPreloader.width, imgPreloader.height);
        }).bind(this);
        imgPreloader.src = this.imageArray[this.activeImage][0];
    },
    resizeImageContainer: function (imgWidth, imgHeight) {
        var widthCurrent = this.outerImageContainer.getWidth();
        var heightCurrent = this.outerImageContainer.getHeight();
        var widthNew = (imgWidth + LightboxOptions.borderSize * 2);
        var heightNew = (imgHeight + LightboxOptions.borderSize * 2);
        var xScale = (widthNew / widthCurrent) * 100;
        var yScale = (heightNew / heightCurrent) * 100;
        var wDiff = widthCurrent - widthNew;
        var hDiff = heightCurrent - heightNew;
        if (hDiff != 0) {
            new Effect.Scale(this.outerImageContainer, yScale, {
                scaleX: false,
                duration: this.resizeDuration,
                queue: "front"
            });
        }
        if (wDiff != 0) {
            new Effect.Scale(this.outerImageContainer, xScale, {
                scaleY: false,
                duration: this.resizeDuration,
                delay: this.resizeDuration
            });
        }
        var timeout = 0;
        if ((hDiff == 0) && (wDiff == 0)) {
            timeout = 100;
            if (Prototype.Browser.IE) {
                timeout = 250;
            }
        } (function () {
            this.prevLink.setStyle({
                height: imgHeight + "px"
            });
            this.nextLink.setStyle({
                height: imgHeight + "px"
            });
            this.imageDataContainer.setStyle({
                width: widthNew + "px"
            });
            this.showImage();
        }).bind(this).delay(timeout / 1000);
    },
    showImage: function () {
        this.loading.hide();
        new Effect.Appear(this.lightboxImage, {
            duration: this.resizeDuration,
            queue: "end",
            afterFinish: (function () {
                this.updateDetails();
            }).bind(this)
        });
        this.preloadNeighborImages();
    },
    updateDetails: function () {
        if (this.imageArray[this.activeImage][1] != "") {
            this.caption.update(this.imageArray[this.activeImage][1]).show();
        }
        if (this.imageArray.length > 1) {
            this.numberDisplay.update(LightboxOptions.labelImage + " " + (this.activeImage + 1) + " " + LightboxOptions.labelOf + "  " + this.imageArray.length).show();
        }
        new Effect.Parallel([new Effect.SlideDown(this.imageDataContainer, {
            sync: true,
            duration: this.resizeDuration,
            from: 0,
            to: 1
        }), new Effect.Appear(this.imageDataContainer, {
            sync: true,
            duration: this.resizeDuration
        })], {
            duration: this.resizeDuration,
            afterFinish: (function () {
                var arrayPageSize = this.getPageSize();
                this.overlay.setStyle({
                    height: arrayPageSize[1] + "px"
                });
                this.updateNav();
            }).bind(this)
        });
    },
    updateNav: function () {
        this.hoverNav.show();
        if (this.activeImage > 0) {
            this.prevLink.show();
        }
        if (this.activeImage < (this.imageArray.length - 1)) {
            this.nextLink.show();
        }
        this.enableKeyboardNav();
    },
    enableKeyboardNav: function () {
        document.observe("keydown", this.keyboardAction);
    },
    disableKeyboardNav: function () {
        document.stopObserving("keydown", this.keyboardAction);
    },
    keyboardAction: function (event) {
        var keycode = event.keyCode;
        var escapeKey;
        if (event.DOM_VK_ESCAPE) {
            escapeKey = event.DOM_VK_ESCAPE;
        } else {
            escapeKey = 27;
        }
        var key = String.fromCharCode(keycode).toLowerCase();
        if (key.match(/x|o|c/) || (keycode == escapeKey)) {
            this.end();
        } else {
            if ((key == "p") || (keycode == 37)) {
                if (this.activeImage != 0) {
                    this.disableKeyboardNav();
                    this.changeImage(this.activeImage - 1);
                }
            } else {
                if ((key == "n") || (keycode == 39)) {
                    if (this.activeImage != (this.imageArray.length - 1)) {
                        this.disableKeyboardNav();
                        this.changeImage(this.activeImage + 1);
                    }
                }
            }
        }
    },
    preloadNeighborImages: function () {
        var preloadNextImage, preloadPrevImage;
        if (this.imageArray.length > this.activeImage + 1) {
            preloadNextImage = new Image();
            preloadNextImage.src = this.imageArray[this.activeImage + 1][0];
        }
        if (this.activeImage > 0) {
            preloadPrevImage = new Image();
            preloadPrevImage.src = this.imageArray[this.activeImage - 1][0];
        }
    },
    end: function () {
        this.disableKeyboardNav();
        this.lightbox.hide();
        new Effect.Fade(this.overlay, {
            duration: this.overlayDuration
        });
        $$("select", "object", "embed").each(function (node) {
            node.style.visibility = "visible";
        });
    },
    getPageSize: function () {
        var xScroll, yScroll;
        if (window.innerHeight && window.scrollMaxY) {
            xScroll = window.innerWidth + window.scrollMaxX;
            yScroll = window.innerHeight + window.scrollMaxY;
        } else {
            if (document.body.scrollHeight > document.body.offsetHeight) {
                xScroll = document.body.scrollWidth;
                yScroll = document.body.scrollHeight;
            } else {
                xScroll = document.body.offsetWidth;
                yScroll = document.body.offsetHeight;
            }
        }
        var windowWidth, windowHeight;
        if (self.innerHeight) {
            if (document.documentElement.clientWidth) {
                windowWidth = document.documentElement.clientWidth;
            } else {
                windowWidth = self.innerWidth;
            }
            windowHeight = self.innerHeight;
        } else {
            if (document.documentElement && document.documentElement.clientHeight) {
                windowWidth = document.documentElement.clientWidth;
                windowHeight = document.documentElement.clientHeight;
            } else {
                if (document.body) {
                    windowWidth = document.body.clientWidth;
                    windowHeight = document.body.clientHeight;
                }
            }
        }
        if (yScroll < windowHeight) {
            pageHeight = windowHeight;
        } else {
            pageHeight = yScroll;
        }
        if (xScroll < windowWidth) {
            pageWidth = xScroll;
        } else {
            pageWidth = windowWidth;
        }
        return [pageWidth, pageHeight];
    }
};
document.observe("dom:loaded", function () {
    new Lightbox();
});