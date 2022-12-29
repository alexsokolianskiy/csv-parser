# csv-parser
--------
По легенде есть таблица в формате CSV с данными о людях со следующими полями:
ID - основной ключ
PARENT_ID - глава цепочки, изначально не заполнен.
EMAIL
CARD
PHONE
И еще какие-то не интересующие нас в данном случае поля - например логины, ФИО и т.д. Поля могут идти в другом порядке да и вообще формат входных данных может плавать. Для нас важно только то, что нужные нам поля точно есть и они гарантированно заполнены.

Известно, что в данных есть дубликаты. Дубликатами считается записи, у которых совпадает хотя бы одно из ключевых полей (EMAIL, CARD, PHONE). Дубликаты необходимо выстроить в цепочки дубликатов и в PARENT_ID записать наименьший ID из цепочки.

Пример входящих данных:
---
ID,PARENT_ID,EMAIL,CARD,PHONE,TMP
1,NULL,email1,card1,phone1,
2,NULL,email1,card2,phone2,
3,NULL,email3,card3,phone3,
4,NULL,email4,card4,phone2,
---

На выходе в записи 1,2,4 мы должны записать PARENT_ID=1 (минимальный ID в цепочке: 1 и 2 имеют общий емайл, а 2 и 4 - телефон) и в третьей строке PARENT_ID=3 (строка без дубля). Пример исходящих данных:
---
ID,PARENT_ID
1,1
2,1
3,3
4,1
---

Еще пару вводных:
1) Решение должно быть на чистом ПХП.
2) Алгоритм должен быть составлен таким образом, чтобы на больших данных выполняться за разумное время или хотя бы иметь запас для оптимизации.
3) Входящие и исходящие данные в формате CSV из стандартных потоков ввода/вывода - это скорее для моего удобства. У меня просто есть пара заготовленных тестов. :)
4) Различными валидациями типа корректность емайла или проверка валидности кредитной карты можно естественно пренебречь. Считаем что данные поступают корректные.