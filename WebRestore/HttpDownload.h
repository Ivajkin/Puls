#ifndef HttpDownload_H
#define HttpDownload_H

#include <QNetworkAccessManager>
#include <QUrl>

QT_BEGIN_NAMESPACE
class QFile;
class QNetworkReply;
QT_END_NAMESPACE

class HttpDownload : public QObject
{
    Q_OBJECT

public:
    HttpDownload(QObject *parent = 0);
    void downloadFile(QUrl url, QString savename = "");
signals:
    void fileSaved(QString);
private slots:
    void startRequest(QUrl url);
    void cancelDownload();
    void httpFinished();
    void httpReadyRead();
    void updateDataReadProgress(qint64 bytesRead, qint64 totalBytes);

private:
    QUrl url;
    QNetworkAccessManager qnam;
    QNetworkReply *reply;
    QFile *file;
    int httpGetId;
    bool httpRequestAborted;
};

#endif
