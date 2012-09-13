/****************************************************************************
** Meta object code from reading C++ file 'filemanager.h'
**
** Created: Thu 13. Sep 17:10:33 2012
**      by: The Qt Meta Object Compiler version 63 (Qt 4.8.1)
**
** WARNING! All changes made in this file will be lost!
*****************************************************************************/

#include "../filemanager.h"
#if !defined(Q_MOC_OUTPUT_REVISION)
#error "The header file 'filemanager.h' doesn't include <QObject>."
#elif Q_MOC_OUTPUT_REVISION != 63
#error "This file was generated using the moc from 4.8.1. It"
#error "cannot be used with the include files from this version of Qt."
#error "(The moc has changed too much.)"
#endif

QT_BEGIN_MOC_NAMESPACE
static const uint qt_meta_data_FileManager[] = {

 // content:
       6,       // revision
       0,       // classname
       0,    0, // classinfo
       5,   14, // methods
       0,    0, // properties
       0,    0, // enums/sets
       0,    0, // constructors
       0,       // flags
       0,       // signalCount

 // slots: signature, parameters, type, tag, flags
      13,   12,   12,   12, 0x0a,
      30,   12,   12,   12, 0x0a,
      67,   12,   12,   12, 0x0a,
      80,   12,   12,   12, 0x08,
     105,   12,   12,   12, 0x08,

       0        // eod
};

static const char qt_meta_stringdata_FileManager[] = {
    "FileManager\0\0processStarted()\0"
    "processError(QProcess::ProcessError)\0"
    "readOutput()\0on_cmdIn_returnPressed()\0"
    "on_buttonEnter_clicked()\0"
};

void FileManager::qt_static_metacall(QObject *_o, QMetaObject::Call _c, int _id, void **_a)
{
    if (_c == QMetaObject::InvokeMetaMethod) {
        Q_ASSERT(staticMetaObject.cast(_o));
        FileManager *_t = static_cast<FileManager *>(_o);
        switch (_id) {
        case 0: _t->processStarted(); break;
        case 1: _t->processError((*reinterpret_cast< QProcess::ProcessError(*)>(_a[1]))); break;
        case 2: _t->readOutput(); break;
        case 3: _t->on_cmdIn_returnPressed(); break;
        case 4: _t->on_buttonEnter_clicked(); break;
        default: ;
        }
    }
}

const QMetaObjectExtraData FileManager::staticMetaObjectExtraData = {
    0,  qt_static_metacall 
};

const QMetaObject FileManager::staticMetaObject = {
    { &QWidget::staticMetaObject, qt_meta_stringdata_FileManager,
      qt_meta_data_FileManager, &staticMetaObjectExtraData }
};

#ifdef Q_NO_DATA_RELOCATION
const QMetaObject &FileManager::getStaticMetaObject() { return staticMetaObject; }
#endif //Q_NO_DATA_RELOCATION

const QMetaObject *FileManager::metaObject() const
{
    return QObject::d_ptr->metaObject ? QObject::d_ptr->metaObject : &staticMetaObject;
}

void *FileManager::qt_metacast(const char *_clname)
{
    if (!_clname) return 0;
    if (!strcmp(_clname, qt_meta_stringdata_FileManager))
        return static_cast<void*>(const_cast< FileManager*>(this));
    return QWidget::qt_metacast(_clname);
}

int FileManager::qt_metacall(QMetaObject::Call _c, int _id, void **_a)
{
    _id = QWidget::qt_metacall(_c, _id, _a);
    if (_id < 0)
        return _id;
    if (_c == QMetaObject::InvokeMetaMethod) {
        if (_id < 5)
            qt_static_metacall(this, _c, _id, _a);
        _id -= 5;
    }
    return _id;
}
QT_END_MOC_NAMESPACE
