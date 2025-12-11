FROM python:3.9-slim

WORKDIR /app

# Copiar requirements primero para mejor cache
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copiar el resto de archivos
COPY . .

EXPOSE 5000

CMD ["python", "app.py"]
