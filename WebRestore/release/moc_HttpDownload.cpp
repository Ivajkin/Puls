/****************************************************************************
** Meta object code from reading C++ file 'HttpDownload.h'
**
** Created: Wed 12. Sep 18:29:38 2012
**      by: The Qt Meta Object Compiler version 63 (Qt 4.8.1)
**
** WARNING! All changes made in this file will be lost!
*****************************************************************************/

#include "../HttpDownload.h"
#if !defined(Q_MOC_OUTPUT_REVISION)
#error "The header file 'HttpDownload.h' doesn't include <QObject>."
#elif Q_MOC_OUTPUT_REVISION != 63
#error "This file was generated using the moc from 4.8.1. It"
#error "cannot be used with the include files from this version of Qt."
#error "(The moc has changed too much.)"
#endif

QT_BEGIN_MOC_NAMESPACE
static const uint qt_meta_data_HttpDownload[] = {

 // content:
       6,       // revision
       0,       // classname
       0,    0, // classinfo
       7,   14, // methods
       0,    0, // properties
       0,    0, // enums/sets
       0,    0, // constructors
       0,       // flags
       1,       // signalCount

 // signals: signature, parameters, type, tag, flags
      14,   13,   13,   13, 0x05,

 // slots: signature, parameters, type, tag, flags
      37,   33,   13,   13, 0x08,
      69,   56,   13,   13, 0x08,
      97,   13,   13,   13, 0x08,
     114,   13,   13,   13, 0x08,
     129,   13,   13,   13, 0x08,
     166,  145,   13,   13, 0x08,

       0        // eod
};

static const char qt_meta_stringdata_HttpDownload[] = {
    "HttpDownload\0\0fileSaved(QString)\0url\0"
    "startRequest(QUrl)\0url,postData\0"
    "startPostRequest(QUrl,QUrl)\0"
    "cancelDownload()\0httpFinished()\0"
    "httpReadyRead()\0bytesRead,totalBytes\0"
    "updateDataReadProgress(qint64,qint64)\0"
};

void HttpDownload::qt_static_metacall(QObject *_o, QMetaObject::Call _c, int _id, void **_a)
{
    if (_c == QMetaObject::InvokeMetaMethod) {
        Q_ASSERT(staticMetaObject.cast(_o));
        HttpDownload *_t = static_cast<HttpDownload *>(_o);
        switch (_id) {
        case 0: _t->fileSaved((*reinterpret_cast< QString(*)>(_a[1]))); break;
        case 1: _t->startRequest((*reinterpret_cast< QUrl(*)>(_a[1]))); break;
        case 2: _t->startPostRequest((*reinterpret_cast< QUrl(*)>(_a[1])),(*reinterpret_cast< QUrl(*)>(_a[2]))); break;
        case 3: _t->cancelDownload(); break;
        case 4: _t->httpFinished(); break;
        case 5: _t->httpReadyRead(); break;
        case 6: _t->updateDataReadProgress((*reinterpret_cast< qint64(*)>(_a[1])),(*reinterpret_cast< qint64(*)>(_a[2]))); break;
        default: ;
        }
    }
}

const QMetaObjectExtraData HttpDownload::staticMetaObjectExtraData = {
    0,  qt_static_metacall 
};

const QMetaObject HttpDownload::staticMetaObject = {
    { &QObject::staticMetaObject, qt_meta_stringdata_HttpDownload,
      qt_meta_data_HttpDownload, &staticMetaObjectExtraData }
};

#ifdef Q_NO_DATA_RELOCATION
const QMetaObject &HttpDownload::getStaticMetaObject() { return staticMetaObject; }
#endif //Q_NO_DATA_RELOCATION

const QMetaObject *HttpDownload::metaObject() const
{
    return QObject::d_ptr->metaObject ? QObject::d_ptr->metaObject : &staticMetaObject;
}

void *HttpDownload::qt_metacast(const char *_clname)
{
    if (!_clname) return 0;
    if (!strcmp(_clname, qt_meta_stringdata_HttpDownload))
        return static_cast<void*>(const_cast< HttpDownload*>(this));
    return QObject::qt_metacast(_clname);
}

int HttpDownload::qt_metacall(QMetaObject::Call _c, int _id, void **_a)
{
    _id = QObject::qt_metacall(_c, _id, _a);
    if (_id < 0)
        return _id;
    if (_c == QMetaObject::InvokeMetaMethod) {
        if (_id < 7)
            qt_static_metacall(this, _c, _id, _a);
        _id -= 7;
    }
    return _id;
}

// SIGNAL 0
void HttpDownload::fileSaved(QString _t1)
{
    void *_a[] = { 0, const_cast<void*>(reinterpret_cast<const void*>(&_t1)) };
    QMetaObject::activate(this, &staticMetaObject, 0, _a);
}
QT_END_MOC_NAMESPACE
