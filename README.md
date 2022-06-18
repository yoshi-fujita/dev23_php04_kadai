# 課題：PHP, MySQLを使ってログイン機能を実装する

## ① 先週作成した「かざい博物館」にログイン機能を追加
- これにログイン機能を追加　https://github.com/yoshi-fujita/dev23_php03_kadai

## ② 工夫した点・こだわった点
- Chromeのconsoleに、ページの遷移と、セッションIDやセッション変数を表示するようにして、それらがどう変化しているのかを見えるようにした。
  - php　は、処理だけのファイルがあり、遷移が見えにくいため
  - regenerateやdestroyなどで、各所のセッションIDやセッション変数がどう変わっているのか分かりにくいため
- Chromeの開発ツールの設定で、Preserve log upon navigation をONにすると、さらに遷移がわかりやすい

<img width="282" alt="Chrome開発ツールの設定変更" src="https://user-images.githubusercontent.com/32793942/174444977-cd224f2f-dd53-4d55-bf90-cdf89a64938a.png">


## ③ 質問・疑問（あれば）
- Lalavelなどのプラットフォームを使うと、こういうユーザー管理の仕組みはライブラリなどを利用できるようになるのだろうか？
  
## ④ その他（感想、シェアしたいことなんでも）
- Tutor_KiKiKiの教え https://github.com/yoshi-fujita/dev23_php04_kadai/blob/main/Tutor%20KiKiKi%20%E3%81%AE%E6%95%99%E3%81%88.png で、PHPとJSがいつどこで実行されているのかがようやく分かったので図示してみた。https://github.com/yoshi-fujita/dev23_php04_kadai/blob/main/%E5%AE%9F%E8%A1%8C%E9%A0%86%E5%BA%8F.pdf
- ユーザの追加・削除処理はまだ実装できていないが、土曜日までには完成したい
- まだ、さくらサーバの立ち上げができておらず、デプロイできずに残念
