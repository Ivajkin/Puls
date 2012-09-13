#include "mainwindow.h"
#include "ui_mainwindow.h"

#include <QtNetwork/QNetworkAccessManager>
#include <QtNetwork/QNetworkReply>
#include <QtNetwork/QNetworkRequest>
#include <QEventLoop>
#include <QUrl>
#include <QObject>
#include <QString>
#include <QTextCodec>
#include <QDebug>
#include <QFile>
#include <QFileInfo>
#include <QMessageBox>
#include <QCloseEvent>
#include <QTimer>
bool Configuration :: loadFromFile(){
    QString path = "data.conf";
    QFileInfo info(path);
    if(!info.exists()){
        qDebug() << "Warning: configuration file " << path << " is not found";
        return false;
    }
    QFile file(path);
    if(file.open(QIODevice :: ReadOnly | QIODevice::Text)){
        while(!file.atEnd()){
            QByteArray byteLine = file.readLine();
            QStringList line = QString(byteLine).split(":");
            QString name = line.first().trimmed();
            QString value = line.last().trimmed();
            confs[name] = value;
        }
    }
    else
        return false;
    file.close();
    return true;
}
bool Configuration :: saveToFile(){
    QString path = "data.conf";
    QFile file(path);
    if(file.open(QIODevice :: WriteOnly | QIODevice::Text)){
        QTextStream out(&file);
        QList<QString> keys = confs.keys();
        for(int i = 0; i < keys.count(); ++i){
            out << keys.at(i) << ": " << confs[keys.at(i)] << "\n";
        }
    }
    else
        return false;
    file.close();
    return true;
}
QString httpGET(QUrl url, const char *setCharset = "UTF-8")
{
    QTextCodec *cyrillicCodec = QTextCodec::codecForName(setCharset);
    QTextCodec::setCodecForLocale(cyrillicCodec);
    QTextCodec::setCodecForCStrings(cyrillicCodec);
    QNetworkAccessManager *manager = new QNetworkAccessManager();
    QNetworkReply *http = manager->get(QNetworkRequest(url));
    QEventLoop eventLoop;
    QObject::connect(http,SIGNAL(finished()),&eventLoop, SLOT(quit()));
    eventLoop.exec();
    QString httpTxt;
    httpTxt = http->readAll();
    delete manager;
    return httpTxt;
}
QString httpPOST(QUrl url, QUrl postData, const char *setCharset = "UTF-8")
{
    QTextCodec *cyrillicCodec = QTextCodec::codecForName(setCharset);
    QTextCodec::setCodecForTr(cyrillicCodec);
    QTextCodec::setCodecForLocale(cyrillicCodec);
    QTextCodec::setCodecForCStrings(cyrillicCodec);
    QNetworkAccessManager *manager = new QNetworkAccessManager();
    QNetworkRequest request(url);
    request.setHeader(QNetworkRequest::ContentTypeHeader,
        "application/x-www-form-urlencoded");
    QNetworkReply *http = manager->post(request,postData.encodedQuery());
    QEventLoop eventLoop;
    QObject::connect(http,SIGNAL(finished()),&eventLoop, SLOT(quit()));
    eventLoop.exec();
    QString httpTxt;
    httpTxt = http->readAll();
    delete manager;
    return httpTxt;
}
MainWindow :: MainWindow(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::MainWindow)
{
    ui -> setupUi(this);
    createActions();
    createTrayIcon();
    createTableMenu();
    trayIcon -> show();

    //connect(trayIcon, SIGNAL(messageClicked()), this, SLOT(setVisible()));
    connect(trayIcon, SIGNAL(activated(QSystemTrayIcon::ActivationReason)),
            this, SLOT(iconActivated(QSystemTrayIcon::ActivationReason)));
    connect(&httpDownload, SIGNAL(fileSaved(QString)), this, SLOT(onFileDownload(QString)));
    conf.loadFromFile();


    dir.setPath(conf["BackUps Folder"]);
    ui -> lineUrl -> setText(conf["Url"]);
    createFileTable();

    ui -> lineLastModified->setDateTime(lastUpdateRecord());

    this->startTimer(1000 * 60 * 60);
    timerEvent(0);
}

MainWindow :: ~MainWindow()
{
    delete ui;
}
void MainWindow::setVisible(bool visible)
{
    minimizeAction->setEnabled(visible);
    restoreAction->setEnabled(!visible);
    QMainWindow::setVisible(visible);
}

void MainWindow :: closeEvent(QCloseEvent *event)
{
    if (trayIcon->isVisible()) {
        QMessageBox::information(this, tr("WebRestore"),
                                 tr("The program will keep running in the "
                                    "system tray. To terminate the program, "
                                    "choose <b>Quit</b> in the context menu "
                                    "of the system tray entry."));
        hide();
        event->ignore();
    }
}

void MainWindow :: on_buttonDownload_clicked()
{
    QUrl postData;
    postData.addQueryItem("data", "string");
    QString text = httpPOST(QUrl("http://" + ui->lineUrl->text()), postData);
    ui->content->setText(text);
    httpDownload.downloadFile(QUrl("http://" + ui->lineUrl->text()),
            conf["BackUps Folder"] + QDateTime::currentDateTime().toString("dd.MM.yyyy hh.mm"), POST, postData);
    conf.confs["Url"] = ui -> lineUrl -> text();
}
void MainWindow :: createActions()
{
    minimizeAction = new QAction(tr("Mi&nimize"), this);
    connect(minimizeAction, SIGNAL(triggered()), this, SLOT(hide()));

    restoreAction = new QAction(tr("&Restore"), this);
    connect(restoreAction, SIGNAL(triggered()), this, SLOT(showNormal()));

    quitAction = new QAction(tr("&Quit"), this);
    connect(quitAction, SIGNAL(triggered()), qApp, SLOT(quit()));

    actionRemove = new QAction(tr("Remove"), this);
    connect(actionRemove, SIGNAL(triggered()), this, SLOT(removeSelectedFiles()));

    actionUpdate = new QAction(tr("Update"), this);
    connect(actionUpdate, SIGNAL(triggered()), this, SLOT(onUpdateTable()));
}

void MainWindow :: createTrayIcon()
{
    trayIconMenu = new QMenu(this);
    trayIconMenu->addAction(minimizeAction);
    trayIconMenu->addAction(restoreAction);
    trayIconMenu->addSeparator();
    trayIconMenu->addAction(quitAction);

    trayIcon = new QSystemTrayIcon(this);
    trayIcon->setContextMenu(trayIconMenu);
    trayIcon->setIcon(style()->standardIcon(QStyle::SP_VistaShield));
}
void MainWindow :: iconActivated(QSystemTrayIcon::ActivationReason reason)
{
    switch (reason) {
    //case QSystemTrayIcon::Trigger:
    case QSystemTrayIcon::DoubleClick:
        setVisible(!this->isVisible());
        break;
    //case QSystemTrayIcon::MiddleClick:
    default:
        ;
    }
}
void MainWindow :: createTableMenu(){
    tableMenu = new QMenu;
    tableMenu->addAction(actionUpdate);
    tableMenu->addSeparator();
    tableMenu->addAction(actionRemove);
    ui -> fileManager -> setContextMenuPolicy(Qt::CustomContextMenu);
    connect(ui->fileManager, SIGNAL(customContextMenuRequested(QPoint)), this, SLOT(contextMenuCall(QPoint)));
}
void MainWindow :: createFileTable(){
    ui -> fileManager -> setColumnCount(3);
    ui -> fileManager -> setRowCount(0);
    ui -> fileManager -> setSelectionBehavior(QAbstractItemView::SelectRows);

    QStringList labels;
    labels << tr("File Name") << tr("Size") << tr("Last Modified");
    ui -> fileManager -> setHorizontalHeaderLabels(labels);
    ui -> fileManager -> horizontalHeader()->setResizeMode(0, QHeaderView::Stretch);
    ui -> fileManager -> verticalHeader()->hide();
    ui -> fileManager -> setShowGrid(false);
    fillFileTable();
}
void MainWindow :: fillFileTable(){
    ui -> fileManager -> clear();
    ui -> fileManager -> setRowCount(0);
    QFileInfoList files = dir.entryInfoList();

    for (int i = 0; i < files.size(); ++i){
        if(files[i].baseName().isEmpty())
            continue;
        QTableWidgetItem *fileNameItem = new QTableWidgetItem(files[i].fileName());
        fileNameItem->setFlags(fileNameItem->flags() ^ Qt::ItemIsEditable);
        QTableWidgetItem *sizeItem = new QTableWidgetItem(tr("%1 KB")
                                 .arg(int((files[i].size() + 1023) / 1024)));
        sizeItem->setFlags(sizeItem->flags() ^ Qt::ItemIsEditable);
        QTableWidgetItem *lastModif = new QTableWidgetItem(files[i].lastModified().toString("hh:mm:ss"));
        lastModif->setFlags(lastModif->flags() ^ Qt::ItemIsEditable);
        //lastModif->setTextAlignment(Qt::AlignRight | Qt::AlignVCenter);
        int row = ui -> fileManager -> rowCount();
        ui -> fileManager -> insertRow(row);
        ui -> fileManager -> setItem(row, 0, fileNameItem);
        ui -> fileManager -> setItem(row, 1, sizeItem);
        ui -> fileManager -> setItem(row, 2, lastModif);
    }
}
void MainWindow :: showMessage(QString title, QString text, int type){
    QSystemTrayIcon::MessageIcon icon = QSystemTrayIcon::MessageIcon(type);
    trayIcon->showMessage(title, text, icon, 10000);
}
void MainWindow :: timerEvent(QTimerEvent *event){
    QDateTime lastModified = ui -> lineLastModified -> dateTime();
    if( 1000 * 60 * 60 * conf["Update Period"].toInt() - 10 <
            lastModified.secsTo(QDateTime::currentDateTime())){
        if(!conf["Url"].isEmpty()){
            showMessage("Information", "Saving...");
            httpDownload.downloadFile(QUrl("http://" + conf["Url"]),
                    conf["BackUps Folder"] + QDateTime::currentDateTime().toString("dd.MM.yyyy hh.mm"));
        }
    }
}
void MainWindow :: onFileDownload(QString filename){
    dir.refresh();
    ui -> lineLastModified->setDateTime(QDateTime::currentDateTime());
    conf.saveToFile();
    fillFileTable();
    showMessage("Information", "Saving complete");
    conf.saveToFile();
}
void MainWindow :: contextMenuCall(const QPoint &pos){
    if(ui -> fileManager -> selectedItems().count() == 0)
        actionRemove->setDisabled(true);
    else actionRemove->setDisabled(false);
    tableMenu->exec(ui->fileManager->mapToGlobal(pos));
}
void MainWindow :: removeSelectedFiles(){
    QList<QTableWidgetItem*> list = ui -> fileManager -> selectedItems();
    int incr = list.count()/3;
    QString files;
    for(int i = 0; i < incr; ++i){
        files += list.at(i)->text() + "\n";
        dir.remove(list.at(i)->text());
    }
    showMessage("Warning", "Removing files: \n" + files, QSystemTrayIcon :: Warning);
    dir.refresh();
    fillFileTable();
    ui -> lineLastModified->setDateTime(lastUpdateRecord());
}
void MainWindow :: onUpdateTable(){
    dir.refresh();
    fillFileTable();
    ui -> lineLastModified->setDateTime(lastUpdateRecord());
    qDebug() << "Size of table: " << sizeof(ui -> fileManager);
    qDebug() << "Size of Qdir: " << sizeof(dir);
    qDebug() << "Size of httpDownload: " << sizeof(httpDownload);
}
QDateTime MainWindow :: lastUpdateRecord(){
    QFileInfoList files = dir.entryInfoList();
    QDateTime lastSave(QDateTime :: fromTime_t(0));
    //qDebug() << lastSave.toString("dd.mm.yyyy");
    for (int i = 0; i < files.size(); ++i){
        if(files[i].baseName().isEmpty())
            continue;
        QDateTime record = files.at(i).lastModified();
        if(lastSave.secsTo(record) > 0){
            lastSave = record;
        }
    }
    qDebug() << "Function: LastUpdateRecord. Result: " << lastSave.toString("dd.MM.yyyy hh:mm");
    return lastSave;
}
/*bool saveToFile(QString text){
    QFile file("test.txt");
    QTextStream stream(&file);
    if(file.open(QIODevice::WriteOnly | QIODevice::Text)){
        stream << text;
    }
    else
        return false;
    file.close();
    return true;
}
void fromUrlToFile(QString url){
    QNetworkAccessManager *manager = new QNetworkAccessManager();
    QNetworkReply *http = manager->get(QNetworkRequest(QUrl(url)));
}
*/
