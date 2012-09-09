#include <QtNetwork>

#include "HttpDownload.h"
#include <QDebug>
HttpDownload :: HttpDownload(QObject *parent) : QObject(parent)
{
}

void HttpDownload :: startRequest(QUrl url)
{
    reply = qnam.get(QNetworkRequest(url));
    connect(reply, SIGNAL(finished()),
            this, SLOT(httpFinished()));
    connect(reply, SIGNAL(readyRead()),
            this, SLOT(httpReadyRead()));
    connect(reply, SIGNAL(downloadProgress(qint64,qint64)),
            this, SLOT(updateDataReadProgress(qint64,qint64)));
}

void HttpDownload :: downloadFile(QUrl url, QString savename)
{
    if(savename.isEmpty()){
        QFileInfo fileInfo(url.path());
        savename = fileInfo.fileName();
    }
    qDebug() << savename;

    if (QFile::exists(savename)) {
    //        обработка ситуации если файл уже есть
        qDebug() << "HTTPDownload: Already exist";
    }

    file = new QFile(savename);
    if (!file->open(QIODevice::WriteOnly)) {
    // если файл нельза записать
        qDebug() << "HTTPDownload: Unable to write";
        delete file;
        file = 0;
        return;
    }
    httpRequestAborted = false;
    startRequest(url);
}

void HttpDownload :: cancelDownload()
{
    qDebug() << "HTTPDownload: Abort";
    httpRequestAborted = true;
    reply->abort();
}

void HttpDownload :: httpFinished()
{
    if (httpRequestAborted) {
        if (file) {
            file->close();
            file->remove();
            delete file;
            file = 0;
        }
        reply->deleteLater();
        return;
    }
    file->flush();
    file->close();
    reply->deleteLater();
    reply = 0;

    emit fileSaved(file->fileName());
    delete file;
    file = 0;
    qDebug() << "HTTPDownload: Finished";

}

void HttpDownload :: httpReadyRead()
{
    if (file)
        file->write(reply->readAll());
}

void HttpDownload :: updateDataReadProgress(qint64 bytesRead, qint64 totalBytes)
{
    if (httpRequestAborted)
        return;
// если нужно информируем о прогрессе
}
