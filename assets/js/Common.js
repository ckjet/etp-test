var baseUrl = '/api';

Ext.override(Ext.grid.Panel, {
    enableColumnHide: false,
    enableColumnMove: false,
    enableDragDrop: false,
    enableHdMenu: false
});

Ext.override(Ext.Component, {hideMode: 'offsets'});

Ext.override(Ext.form.Panel, {
    labelAlign: 'top',
    autoScroll: true,
    padding: 10
});

Ext.override(Ext.window.Window, {
    padding: 5,
    modal: true,
    closable: true,
    closeAction: 'hide'
});

Ext.override(Ext.PagingToolbar, {
    beforePageText: 'Страница',
    afterPageText: 'из {0}',
    displayMsg: 'Записи {0} - {1} из {2}',
    emptyMsg: 'Нет данных для отображения'
});

Ext.ns('App.Components');

Ext.ns('App.Stores');