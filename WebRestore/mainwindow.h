#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include "HttpDownload.h"
#include <QSystemTrayIcon>
#include <QMap>
#include <QDate>
#include <QDir>
namespace Ui {
class MainWindow;
}
struct Configuration{
    QMap<QString, QString> confs;
    Configuration(){
        confs["BackUps Folder"] = "BackUps/";
        confs["Url"] = "somewhere.com/index.php";
        confs["Port"] = 21;
    }

    bool loadFromFile();
    bool saveToFile();
    QString operator[](QString key){
        return confs.value(key, "");
    }
};
class MainWindow : public QMainWindow
{
    Q_OBJECT
    
public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();

    void setVisible(bool visible = false);

protected:
    void closeEvent(QCloseEvent *event);

private slots:
    void onFileDownload(QString);
    void iconActivated(QSystemTrayIcon::ActivationReason reason);
    void showMessage(QString title, QString text,int type = QSystemTrayIcon::Information);
    void on_buttonDownload_clicked();

private:
    void createActions();
    void createTrayIcon();
    void createFileTable();
    void fillFileTable();
private:
    Ui::MainWindow *ui;

    QAction *minimizeAction;
    QAction *restoreAction;
    QAction *quitAction;

    QDir dir;
    Configuration conf;
    QSystemTrayIcon *trayIcon;
    QMenu *trayIconMenu;

    HttpDownload httpDownload;
};

#endif // MAINWINDOW_H
