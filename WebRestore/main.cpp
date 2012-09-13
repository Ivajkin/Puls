#include <QtGui/QApplication>
#include "mainwindow.h"
#include <QTextCodec>

int main(int argc, char *argv[])
{
    QTextCodec *cyrillicCodec = QTextCodec::codecForName("Windows-1251");
    QTextCodec::setCodecForTr(cyrillicCodec);
    QTextCodec::setCodecForLocale(cyrillicCodec);
    QTextCodec::setCodecForCStrings(cyrillicCodec);
    QApplication a(argc, argv);
    MainWindow w;
    w.show();
    
    return a.exec();
}
