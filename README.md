# 發噗幫手

## 這啥

利用噗浪 API 製作的發噗工具。

以 PHP + Laravel 製作，目前只有基本發噗功能，以及可以觀看最新一則自己的噗。

## 螢幕擷圖

![尚未登入的起始畫面](docs/plurk-login.png)

![透過噗浪授權的過程畫面](docs/plurk-authorization.png)

![登入後的畫面](docs/dashboard.png)

## 系統需求

- Linux（其他系統沒試過）
- Composer（PHP 套件管理器）
- Docker
- Docker Compose

## 安裝

請先從範本建立環境設定檔：

```shell
cp .env.example .env
```

應用程式的 key、secret 等資料需在 <https://www.plurk.com/PlurkApp/> 申請，並填到 `.env` 檔案的開頭兩行內。

環境設定檔搞定之後，再執行：

```shell
composer install
sail artisan key:generate
sail up -d
sail artisan migrate
```

等 Docker 容器啟動後再瀏覽 <http://localhost/>。
