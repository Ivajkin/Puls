#include "filemanager.h"
#include "ui_filemanager.h"
#include <QDebug>
FileManager::FileManager(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::FileManager)
{
    ui->setupUi(this);
    ui->treeFileManager->setColumnCount(9);
    ui->treeFileManager->header()->resizeSection(1, 25);
    ui->treeFileManager->header()->resizeSection(2, 80);
    ui->treeFileManager->header()->resizeSection(3, 80);
    ui->treeFileManager->header()->resizeSection(5, 40);
    ui->treeFileManager->header()->resizeSection(6, 30);
    ui->treeFileManager->header()->resizeSection(7, 40);
    process = new QProcess(this);
    process->setReadChannelMode(QProcess::SeparateChannels);
    process->setReadChannel(QProcess::StandardOutput);
    connect(process, SIGNAL(readyRead()),SLOT(readOutput()));
    connect(process, SIGNAL(started()),SLOT(processStarted()));
    connect(process, SIGNAL(error(QProcess::ProcessError)),SLOT(processError(QProcess::ProcessError)));
    QString command = "sftp/psftp.exe ftp.coreatrade.com -l core5429 -pw sTXZ1hqe -b sftp/psftp.bat";
    process->start(command);
}

FileManager::~FileManager(){
    delete ui;
    process->close();
    delete process;
}

void FileManager :: readOutput(){
    qDebug() << "Read";
    QString str = tr(process->readAll());
    parseDirContent(str);
    ui->cmdOut->append(str);
}
void FileManager :: processStarted(){
    qDebug() << "Started";
}
void FileManager :: processError(QProcess::ProcessError error){
    qDebug() << "Error:" << error;
}

void FileManager::on_cmdIn_returnPressed(){
    qDebug() << "Entered";
    //process->W
    process->write(ui->cmdIn->text().toAscii());
    if(!process->waitForBytesWritten())
        qDebug() << "No writing";
    process->closeWriteChannel();
}

void FileManager::on_buttonEnter_clicked(){
    process->start("sftp/psftp core5429@ftp.coreatrade.com -pw sTXZ1hqe -b sftp/psftp.bat");
}
void FileManager :: parseDirContent(QString str){
    //qDebug() << str.split("\n").at(0);
    ui->treeFileManager->clear();
    QStringList files = str.split("\n");
    //ui->FileManager
    for(int i = 2; i < files.count(); ++i){
        QString content;
        QStringList fileInfo = files.at(i).split(QRegExp("\\s{1,}"));
        QTreeWidgetItem* item = new QTreeWidgetItem(ui->treeFileManager, fileInfo);
    }
    ui->treeFileManager->resizeColumnToContents(4);
}
