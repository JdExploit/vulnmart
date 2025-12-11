from flask import Flask, request, jsonify
import os
import subprocess
import mysql.connector

app = Flask(__name__)

# ¡VULNERABILIDAD: Sin autenticación!
ADMIN_SECRET = os.getenv('ADMIN_SECRET', 'supersecretkey123')

def get_db_connection():
    """Conexión a la base de datos (vulnerable)"""
    return mysql.connector.connect(
        host=os.getenv('DB_HOST', 'database'),
        user=os.getenv('DB_USER', 'root'),
        password=os.getenv('DB_PASSWORD', 'toor123!'),
        database='vulnmart'
    )

@app.route('/')
def index():
    return "Admin Service Running - Internal Use Only"

@app.route('/execute', methods=['GET', 'POST'])
def execute_command():
    """¡VULNERABILIDAD: Ejecución de comandos sin validación!"""
    cmd = request.args.get('cmd') or request.form.get('cmd')
    
    if not cmd:
        return jsonify({'error': 'No command provided'}), 400
    
    try:
        # ¡CRÍTICO: Ejecuta comandos del sistema directamente!
        result = subprocess.check_output(cmd, shell=True, stderr=subprocess.STDOUT)
        return jsonify({
            'command': cmd,
            'output': result.decode('utf-8')
        })
    except subprocess.CalledProcessError as e:
        return jsonify({'error': str(e.output)}), 500

@app.route('/admin/users', methods=['GET'])
def get_users():
    """Endpoint para obtener usuarios (vulnerable)"""
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    
    # ¡VULNERABILIDAD: Sin validación de parámetros!
    limit = request.args.get('limit', 100)
    query = f"SELECT * FROM users LIMIT {limit}"
    
    cursor.execute(query)
    users = cursor.fetchall()
    
    cursor.close()
    conn.close()
    
    return jsonify(users)

@app.route('/admin/backup', methods=['POST'])
def create_backup():
    """Endpoint para crear backup (vulnerable)"""
    # ¡VULNERABILIDAD: Path traversal posible!
    backup_path = request.json.get('path', '/tmp/backup.sql')
    
    cmd = f"mysqldump -h database -u root -ptoor123! vulnmart > {backup_path}"
    subprocess.run(cmd, shell=True)
    
    return jsonify({'message': f'Backup created at {backup_path}'})

@app.route('/debug', methods=['GET'])
def debug_info():
    """Endpoint de debug (expone información sensible)"""
    info = {
        'env_vars': dict(os.environ),
        'cwd': os.getcwd(),
        'files': os.listdir('.'),
        'process': os.getpid()
    }
    return jsonify(info)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)  # ¡VULNERABILIDAD: Debug mode en producción!