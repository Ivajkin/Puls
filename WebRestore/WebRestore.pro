#-------------------------------------------------
#
# Project created by QtCreator 2012-09-05T10:27:22
#
#-------------------------------------------------

QT       += core gui
QT       += network
TARGET = WebRestore
TEMPLATE = app


SOURCES += main.cpp\
        mainwindow.cpp \
    HttpDownload.cpp \
    filemanager.cpp

HEADERS  += mainwindow.h \
    HttpDownload.h \
    filemanager.h

FORMS    += mainwindow.ui \
    filemanager.ui

INCLUDEPATH += C:/OpenSSL-Win32/include

RESOURCES += \
    resources.qrc
