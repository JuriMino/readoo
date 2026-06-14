# PHP8.5をFPM(FastCGI)をベースイメージとして使う
FROM php:8.5-fpm

# 必要なパッケージとPHP拡張のインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Node.jsのインストール
RUN curl -fsSL https://deb.nodesource.com/setup_26.x | bash - \
    && apt-get install -y nodejs

# Composerのインストール
# Composerの公式イメージから実行ファイルだけをコピー
COPY --from=composer:2.10 /usr/bin/composer /usr/bin/composer

# コンテナ内の作業ディレクトリです。Laravelのファイルはここに置かれる
WORKDIR /var/www/html