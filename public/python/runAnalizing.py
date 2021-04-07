import pymysql
import sys
import json
import os

import pandas as pd
import tensorflow as tf
from tensorflow import keras
from sklearn.model_selection import train_test_split
import createModel

cdir = os.getcwd()

# Загружаем обученную модель из папки
checkpoint_path = cdir + "/python/training_1/cp.ckpt"

model = createModel.create()
model.load_weights(checkpoint_path)


# # Получаем период за который необходимо оценить данные
dateRange = sys.argv[1]
# dates = json.loads(sys.argv[1])

# print(dates)

connection = pymysql.connect(host='localhost',
                             user='root',
                             password='root',
                             database='digit',
                             cursorclass=pymysql.cursors.DictCursor)

# Получаем данные за указанный период.
# В цикле оцениваем каждый набор данных.
# Записываем результат оценки обратно в таблицу

with connection:
    with connection.cursor() as cursor:
        # sql = "SELECT `id`, `temperature`, `bend` FROM `module_data` WHERE `created_at` between %s and %s"
        sql = "SELECT `id`, `temperature`, `bend` FROM `module_data`"
        # cursor.execute(sql, (dates['dateFrom'], dates['dateTo']))
        cursor.execute(sql)

        data = cursor.fetchall()

        for record in data:

            predictions = model.predict(
                [[record.get('temperature'), record.get('bend')]])

            result = predictions[0][0]

            result = 1 if result > 0 else 0

            # with connection.cursor() as cursor:
            # Сохраняем результат оценки
            sql = "UPDATE `module_data` SET `is_real` = %s WHERE `id` = %s"
            cursor.execute(sql, (result, record.get('id')))

            # connection is not autocommit by default. So you must commit to save
            # your changes.
            connection.commit()


# probability_model = tf.keras.Sequential([
#     model,
#     tf.keras.layers.Softmax()
# ])


# probability_model(feature_test[:5])
