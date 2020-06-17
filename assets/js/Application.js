Ext.ns('App.Stores');

App.StatisticGrid = Ext.extend(Ext.grid.Panel, {
    title: 'Статистика',
    pageSize: 50,
    iconCls: 'fa fa-id-card',
    autoExpandColumn: 'ipColumn',
    searchWindowInited: false,
    initComponent: function () {
        this.store = new Ext.data.Store({
            reader: new Ext.data.JsonReader(),
            proxy: new Ext.data.HttpProxy({
                url: baseUrl + '/statistic/list',
                method: 'GET'
            })
        });
        this.createColumns();
        this.createTools();
        this.on({
            afterrender: {
                fn: function () {
                    this.store.load({
                        params: {
                            start: 0,
                            limit: this.pageSize
                        }
                    });
                }
            },
            scope: this
        });
        App.StatisticGrid.superclass.initComponent.apply(this, arguments);
    },
    createTools: function () {
        this.tbar = new Ext.Toolbar({
            items: [{
                text: 'Искать',
                iconCls: 'fa fa-search',
                handler: function () {
                    this.showSearchWindow();
                    this.app.cards.getLayout().setActiveItem(0);
                },
                scope: this
            }]
        });
        this.bbar = new Ext.PagingToolbar({
            store: this.store,
            displayInfo: true,
            pageSize: this.pageSize
        });
    },
    createColumns: function () {
        this.sm = new Ext.selection.RowModel();
        this.columns = [{
            header: '#',
            width: 80,
            sortable: false,
            hidden: true,
            dataIndex: 'id'
        }, {
            header: 'IP',
            sortable: false,
            dataIndex: 'ip',
            width: 220,
        }, {
            header: 'Браузер',
            sortable: true,
            dataIndex: 'browser',
            width: 200,
        }, {
            header: 'OS',
            sortable: true,
            dataIndex: 'os',
            width: 200,
        }, {
            header: 'URL входной',
            sortable: false,
            dataIndex: 'url_from',
            width: 270,
        }, {
            header: 'URL последний',
            sortable: false,
            dataIndex: 'url_to',
            width: 270,
        }, {
            header: 'URL всего',
            sortable: false,
            dataIndex: 'url_total',
            width: 100,
        }];
    },
    showSearchWindow: function () {
        if (!this.searchWindowInited) {
            this.searchWindow = new App.StatisticGrid.SearchWindow({cmp: this});
            this.searchWindowInited = true;
        }
        this.searchWindow.show();
    }
});

App.StatisticGrid.SearchWindow = Ext.extend(Ext.window.Window, {
    title: 'Поиск',
    width: 450,
    padding: 5,
    iconCls: 'fa fa-search',
    initComponent: function () {
        this.createTools();
        this.createForms();
        App.StatisticGrid.SearchWindow.superclass.initComponent.apply(this, arguments);
    },
    createTools: function () {
        this.tbar = new Ext.Toolbar({
            items: {
                text: 'Сбросить',
                handler: function () {
                    this.ipField.reset();
                    this.cmp.store.load({
                        params: {
                            offset: 0,
                            limit: this.cmp.pageSize
                        }
                    });
                    this.hide();
                },
                scope: this
            }
        })
    },
    createForms: function () {
        this.ipField = new Ext.form.TextField({
            fieldLabel: 'Поиск по IP'
        });
        this.items = new Ext.Panel({
            layout: 'column',
            defaults: {xtype: 'container'},
            items: [{
                layout: 'form',
                labelAlign: 'top',
                //padding: 10,
                defaults: {anchor: '100%', labelAlign: 'top'},
                width: 350,
                items: [this.ipField]
            }, {
                defaults: {
                    xtype: 'button',
                    text: 'Искать',
                    scope: this,
                    width: 70
                },
                items: [{
                    style: 'margin-top: 31px; display: block;',
                    handler: function () {
                        this.cmp.store.load({
                            params: {
                                offset: 0,
                                limit: this.cmp.pageSize,
                                ipQuery: this.ipField.getValue()
                            }
                        });
                        this.hide();
                    },
                    scope: this
                }]
            }]
        });
    }
});

App.layout = function () {
    return {
        init: function () {
            this.grid = new App.StatisticGrid();

            this.cards = new Ext.Container({
                layout: 'card',
                region: 'center',
                activeItem: 0,
                defaults: {app: this},
                items: [this.grid]
            });

            new Ext.Viewport({
                layout: 'border',
                defaults: {
                    autoScroll: true
                },
                items: [this.cards]
            });

            document.getElementById('extLoading').remove();
        }
    }
}();

Ext.onReady(App.layout.init, App.layout, {
    single: true
});