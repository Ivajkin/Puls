/********************************************************************************
** Form generated from reading UI file 'filemanager.ui'
**
** Created: Thu 13. Sep 12:59:09 2012
**      by: Qt User Interface Compiler version 4.8.1
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_FILEMANAGER_H
#define UI_FILEMANAGER_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QHeaderView>
#include <QtGui/QLineEdit>
#include <QtGui/QPushButton>
#include <QtGui/QTextEdit>
#include <QtGui/QTreeWidget>
#include <QtGui/QWidget>

QT_BEGIN_NAMESPACE

class Ui_FileManager
{
public:
    QTreeWidget *treeFileManager;
    QTextEdit *cmdOut;
    QLineEdit *cmdIn;
    QPushButton *buttonEnter;

    void setupUi(QWidget *FileManager)
    {
        if (FileManager->objectName().isEmpty())
            FileManager->setObjectName(QString::fromUtf8("FileManager"));
        FileManager->resize(775, 585);
        treeFileManager = new QTreeWidget(FileManager);
        QTreeWidgetItem *__qtreewidgetitem = new QTreeWidgetItem();
        __qtreewidgetitem->setText(0, QString::fromUtf8("1"));
        treeFileManager->setHeaderItem(__qtreewidgetitem);
        treeFileManager->setObjectName(QString::fromUtf8("treeFileManager"));
        treeFileManager->setGeometry(QRect(60, 160, 301, 241));
        cmdOut = new QTextEdit(FileManager);
        cmdOut->setObjectName(QString::fromUtf8("cmdOut"));
        cmdOut->setGeometry(QRect(370, 160, 291, 241));
        cmdIn = new QLineEdit(FileManager);
        cmdIn->setObjectName(QString::fromUtf8("cmdIn"));
        cmdIn->setGeometry(QRect(370, 410, 221, 20));
        buttonEnter = new QPushButton(FileManager);
        buttonEnter->setObjectName(QString::fromUtf8("buttonEnter"));
        buttonEnter->setGeometry(QRect(600, 410, 75, 23));

        retranslateUi(FileManager);

        QMetaObject::connectSlotsByName(FileManager);
    } // setupUi

    void retranslateUi(QWidget *FileManager)
    {
        FileManager->setWindowTitle(QApplication::translate("FileManager", "Form", 0, QApplication::UnicodeUTF8));
        buttonEnter->setText(QApplication::translate("FileManager", "Enter", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class FileManager: public Ui_FileManager {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_FILEMANAGER_H
