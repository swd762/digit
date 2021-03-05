import os
import pandas as pd
import tensorflow as tf
from tensorflow import keras
from sklearn.model_selection import train_test_split

df = pd.read_csv('./data.csv')

checkpoint_path = "training_1/cp.ckpt"
checkpoint_dir = os.path.dirname(checkpoint_path)

cp_callback = tf.keras.callbacks.ModelCheckpoint(filepath=checkpoint_path,
                                                 save_weights_only=True,
                                                 verbose=1)


features = df.drop('true', axis=1)
labels = df['true']

feature_train, feature_test, label_train, label_test = train_test_split(
    features, labels, test_size=0.33)

# Зарезервируем 100 примеров для валидации
feature_val = feature_train[-100:]  # последние 100
label_val = label_train[-100:]

feature_train = feature_train[:-100]  # все, кроме последних 100
label_train = label_train[:-100]


model = tf.keras.models.Sequential([
    tf.keras.layers.Dense(128, activation='relu'),
    tf.keras.layers.Dropout(0.2),
    tf.keras.layers.Dense(1)
])

model.compile(
    optimizer=keras.optimizers.RMSprop(),  # Optimizer
    # optimizer='adam',  # Optimizer
    # Минимизируемая функция потерь
    # loss=keras.losses.SparseCategoricalCrossentropy(),
    loss=keras.losses.BinaryCrossentropy(),
    # Список метрик для мониторинга
    # metrics=[keras.metrics.SparseCategoricalAccuracy()])
    metrics=[keras.metrics.BinaryAccuracy()])

history = model.fit(feature_train, label_train, batch_size=32,
                    epochs=10, validation_data=(feature_val, label_val), callbacks=[cp_callback])

model.summary()

print('\nhistory dict:', history.history)

# Оценим модель на тестовых данных, используя "evaluate"
print('\n# Оцениваем на тестовых данных')
results = model.evaluate(feature_test, label_test, batch_size=32)
print('test loss, test acc:', results)

# Сгенерируем прогнозы (вероятности - выходные данные последнего слоя)
# на новых данных с помощью "predict"
print('\n# Генерируем прогнозы для 3 образцов')
print('Образцы:', feature_test[:3])
predictions = model.predict(feature_test[:3])
print('размерность прогнозов:', predictions.shape)
print('Прогнозы:', predictions)


probability_model = tf.keras.Sequential([
    model,
    tf.keras.layers.Softmax()
])


probability_model(feature_test[:5])
