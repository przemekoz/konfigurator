var lang = getStorageLang();
var dictionary = {};

var app = new Vue({
  data: {
    elementType: 'LAMP',
    filter: {
        category: 'CITY',
        connection: 'UP'
    },
    current: {
        lamp: 0,
        kinkiet: 0,
        crown: 0,
        column: 0,
        other: 0
    },
    email: '',
    name: '',
    emailMessage: '',
    emailFail: false,
    mailSending: false,
    mailSended: false,
    withCrown: false,
    chooser: false,
    preview: false,
    previewButtons: false,
    columnsLoaded: true,
    crownsLoaded: true,
    kinkietLoaded: true,
    lamps: [],
    sizes: [],
    others: [],
    crowns: [],
    kinkiet: [],
    columns: [],
    materials: [],
  },
  watch: {
    filter: {
        handler: function(newFilter) {
            this.getData(newFilter);
        },
        deep: true
    },
    elementType: function(newType) {
        this.getData(this.filter);
    },
    current: {
        handler: function(newCurrent) {
            this.previewButtons = true;
        },
        deep: true
    }
  },
  methods: {
    lampChanged: function (index) {
        this.reloadAfterLampChange(index);
        this.current.lamp = index;
    },
    otherChanged: function (index) {
        this.current.other = index;
    },
    crownChanged: function (index) {
        this.reloadAfterCrownChange(index);
        this.current.crown = index;
    },
    columnChanged: function (index) {
        this.current.column = index;
    },
    kinkietChanged: function (index) {
        this.current.kinkiet = index;
    },
    getData: function(newFilter) {
        if (this.elementType === 'LAMP') {
            if (this.withCrown) {
                this.getLamps(newFilter);
            }
            else {
                this.getLampsWithoutCrowns(newFilter);
            }
        }
        else if (this.elementType === 'KINKIET') {
            this.getKinkiet(newFilter);
        }
        else if (this.elementType === 'OTHER') {
            this.getOthers(newFilter);
        }
    },
    isLamp: function () {
        return this.elementType === 'LAMP';
    },
    isNotLamp: function () {
        return !this.isLamp();
    },
    getLamps: function (filter) {
        clearCanvas(this);
        this.$http.get(BASE_URL + 'api/lamps.json', {params: filter}).then(function (response) {
            this.chooser = true;
            // get body data
            this.lamps = response.body.lamps;
            this.crowns = response.body.crowns;
            this.columns = response.body.columns;
            this.preview = response.body.preview;
        }, fail);
    },
    getLampsWithoutCrowns: function (filter) {
        clearCanvas(this);
        this.$http.get(BASE_URL + 'api/lampcolumn.json', {params: filter}).then(function (response) {
            this.chooser = true;
            // get body data
            this.lamps = response.body.lamps;
            this.columns = response.body.columns;
            this.preview = response.body.preview;
        }, fail);
    },
    getOthers: function (filter) {
          clearCanvas(this);
          this.$http.get(BASE_URL + 'api/others.json', {params: _.omit(filter, ['connection', 'size', 'material'])}).then(function (response) {
            this.chooser = true;
            // get body data
            this.others = response.body.others;
            this.preview = response.body.preview;
        }, fail);
    },
    getKinkiet: function (filter) {
        clearCanvas(this);
        this.$http.get(BASE_URL + 'api/kinkiet.json', {params: _.omit(filter, ['size', 'material'])}).then(function (response) {
            this.chooser = true;
            // get body data
            this.lamps = response.body.lamps;
            this.kinkiet = response.body.kinkiet;
            this.preview = response.body.preview;
        }, fail);
    },
    getPdf: function () {
        this.pdfNotLoad = false;
        getAction(this, 'getPdf');
    },
    getPreview: function () {
        this.preview = false;
        getAction(this, 'getPreview');
    },
    sendMail: function () {
        if (this.email) {
          this.emailFail = false;
          this.mailSending = true;
          getAction(this, 'sendMail');
        }
        else {
          this.emailFail = true;
        }
    },
    getConfiguration: function () {
        this.$http.get(BASE_URL + 'api/config.json').then(function (response) {

            dictionary = response.body.dictionary;
            this.sizes = response.body.sizes;
            this.materials = response.body.materials;

            this.filter.size = response.body.defaultSize;
            this.filter.material = response.body.defaultMaterial;
            app.$mount('#vueApp');
        }, fail);
    },
    changedFilter: function() {
      var context = this;
      Vue.nextTick(function () {
          context.getData(context.filter);
      });
    },
    isCity: function() {
      return this.filter.category === 'CITY';
    },
    isHome: function() {
      return this.filter.category === 'HOME';
    },
    dict: function (value) {
        return getTranslation(value);
    },
    clearEmailForm: function() {
      this.name = '';
      this.email = '';
      this.emailMessage = '';
      this.mailSending = false;
      this.mailSended = false;
    },
    reloadAfterCrownChange: function (index) {
        // need to make decision - always preview will be refreshed, or only be user click 'preview'
        // this.preview = false;
        this.columnsLoaded = false;
        var params = _.extend(_.clone(this.filter), {
            crownId: getElementId(index, 'CROWN')
            // ,lampId: getElementId(index, 'LAMP')
        });
        this.$http.get(BASE_URL + 'api/lamps.json', {params: params}).then(function (response) {
            this.columnsLoaded = true;
            // this.current.lamp = 0;
            this.columns = response.body.columns;
            // this.preview = response.body.preview;
        }, fail);
    },
    reloadAfterLampChange: function (index) {
        // this.preview = false;
        this.columnsLoaded = false;
        this.crownsLoaded = false;
        this.kinkietLoaded = false;
        var params = _.extend(_.clone(this.filter), 
            {lampId: getElementId(index, 'LAMP')});

        var url = 'api/lampcolumn.json';
        if (this.elementType === 'LAMP' && this.withCrown) {
            url = 'api/lamps.json';
        }
        else if (this.elementType === 'KINKIET') {
            url = 'api/kinkiet.json';
        }
        this.$http.get(BASE_URL + url, {params: params}).then(function (response) {
            this.columnsLoaded = true;
            this.crownsLoaded = true;
            this.kinkietLoaded = true;
            this.current.kinkiet = 0;
            this.current.crown = 0;
            this.current.column = 0;
            // this.preview = response.body.preview;
            this.crowns = response.body.crowns || [];
            this.kinkiet = response.body.kinkiet || [];
            this.columns = response.body.columns || [];
        }, fail);
    }
  },
  filters: {
    dict: function (value) {
        return getTranslation(value);
    }
  },
  components: {
    'carousel-3d': Carousel3d.Carousel3d,
    'slide': Carousel3d.Slide
  }
});

app.getConfiguration();
app.getLampsWithoutCrowns();

// -----------------------------------------------------------------------------

function getAction(th, action) {
    if (th.elementType === 'LAMP') {
        if (th.withCrown) {
            functions[action](th, lampParams(th));
        }
        else {
            functions[action](th, lampWithoutCrownParams(th));
        }
    }
    else if (th.elementType === 'KINKIET') {
        functions[action](th, kinkietParams(th));
    }
    else if (th.elementType === 'OTHER') {
        functions[action](th, {firstId: getElementId(th.current.other, 'OTHER')});
    }
}

function getElementId(index, type) {
    if (type === 'COLUMN') {
        return app.columns[index]['col.id'];
    }
    if (type === 'CROWN') {
        return app.crowns[index]['cro.id'];
    }
    if (type === 'LAMP') {
        return app.lamps[index]['lam.id'];
    }
    if (type === 'KINKIET') {
        return app.kinkiet[index]['kin.id'];
    }
    if (type === 'OTHER') {
        return app.others[index]['id'];
    }
}

function getStorageLang() {
    if (LANG) {
        return LANG;
    }
    if (typeof(Storage) !== "undefined") {
        var lang = localStorage.getItem("promar-site-lang");
        return lang ? lang.toLowerCase() : 'pl';
    } else {
        return 'pl';
    }
}

function getTranslation(value) {
    if (!dictionary[lang]) {
        return value;
    }
    if (!dictionary[lang][value]) {
        return value;
    }
    return dictionary[lang][value];
}

function lampParams(th) {
    return {
      firstId: getElementId(th.current.column, 'COLUMN'),
      secondId: getElementId(th.current.crown, 'CROWN'),
      thirdId: getElementId(th.current.lamp, 'LAMP')
    };
}

function kinkietParams(th) {
    return {
        firstId: getElementId(th.current.kinkiet, 'KINKIET'),
        secondId: getElementId(th.current.lamp, 'LAMP')
    };
}

function lampWithoutCrownParams(th) {
    return {
        firstId: getElementId(th.current.column, 'COLUMN'),
        secondId: getElementId(th.current.lamp, 'LAMP')
    };
}

function paramsToUrl(paramsObject) {
    var stringParam = [];
    _.each(paramsObject, function (item, key) {
    	stringParam.push(key + '=' + item);
    });
    return stringParam.join('&');
}

var functions = {
  getPdf: function(th, params) {
    // location.href = BASE_URL + 'api/pdf.php?'+ paramsToUrl(params);
      window.open(BASE_URL + 'api/pdf.php?'+ paramsToUrl(params));
  },
  getPreview: function(th, params) {
      th.$http.get(BASE_URL + 'api/preview.json', {params: params}).then(function (response) {
          th.preview = response.body.preview;
      }, fail);
  },
  sendMail: function(th, params) {
      th.$http.get(BASE_URL + 'api/sendmail.json', {params: _.extend(params, {email: th.email, name: th.name, message: th.emailMessage})}).then(function (response) {
          th.mailSending = false;
          th.mailSended = (response.body.result === 'ok');
      }, fail);
  }
};

function clearCanvas(th) {
    th.preview = false;
    th.chooser = false;
    th.previewButtons = false;
    th.current.lamp = 0;
    th.current.kinkiet = 0;
    th.current.crown = 0;
    th.current.column = 0;
    th.current.other = 0;
}

function fail(response) {
    // fail response
}