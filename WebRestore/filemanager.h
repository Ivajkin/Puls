#ifndef FILEMANAGER_H
#define FILEMANAGER_H

#include <QWidget>
#include <QProcess>

namespace Ui {
class FileManager;
}

class FileManager : public QWidget
{
    Q_OBJECT
    
public:
    explicit FileManager(QWidget *parent = 0);
    ~FileManager();
public:
private:
public slots:
    void processStarted();
    void processError(QProcess::ProcessError);
    void readOutput();
private slots:
    void on_cmdIn_returnPressed();

    void on_buttonEnter_clicked();
private:
    void parseDirContent(QString str);
private:
    Ui::FileManager *ui;

    QProcess* process;
};

#endif // FILEMANAGER_H
