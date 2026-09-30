
from flask import Flask, render_template, request, redirect, url_for, session
from database_config import get_connection

app = Flask(__name__)
app.secret_key = 'secret_key_here'

@app.route('/')
def index():
    return render_template('coverpage.html')

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form['email']
        password = request.form['password']
        conn = get_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM Voter WHERE Email=? AND Password=?", (email, password))
        user = cursor.fetchone()
        if user:
            session['voter_id'] = user[0]
            return redirect('/voter_dashboard')
        else:
            return "Login failed"
    return render_template('loginpage.html')

@app.route('/admin_login', methods=['GET', 'POST'])
def admin_login():
    if request.method == 'POST':
        email = request.form['email']
        password = request.form['password']
        conn = get_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM Admin WHERE Email=? AND Password=?", (email, password))
        user = cursor.fetchone()
        if user:
            session['admin_id'] = user[0]
            return redirect('/admin_dashboard')
        else:
            return "Admin login failed"
    return render_template('admin_dashboard.html')

@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        fullname = request.form['fullname']
        email = request.form['email']
        password = request.form['password']
        conn = get_connection()
        cursor = conn.cursor()
        cursor.execute("INSERT INTO Voter (Fullname, Email, Password) VALUES (?, ?, ?)",
                       (fullname, email, password))
        conn.commit()
        return redirect('/voter_dashboard')
    return render_template('voter_registration_page.html')

@app.route('/vote', methods=['GET', 'POST'])
def vote():
    if request.method == 'POST':
        candidate_id = request.form['candidate_id']
        voter_id = session.get('voter_id')
        conn = get_connection()
        cursor = conn.cursor()
        cursor.execute("INSERT INTO Vote (VoterID, CandidateID) VALUES (?, ?)", (voter_id, candidate_id))
        conn.commit()
        return render_template('vote_casted_successfully.html')
    return render_template('vote.html')

@app.route('/about_us')
def about_us():
    return render_template('about_us.html')

@app.route('/candidate_dashboard')
def candidate_dashboard():
    return render_template('candidate_dashboard.html')

@app.route('/candidate_list')
def candidate_list():
    return render_template('candidate_list.html')

@app.route('/candidate_registeration_page')
def candidate_registeration_page():
    return render_template('candidate_registeration_page.html')

@app.route('/candidcy_withdrawed_sucessfully')
def candidcy_withdrawed_sucessfully():
    return render_template('candidcy_withdrawed_sucessfully.html')

@app.route('/contact_us')
def contact_us():
    return render_template('contact_us.html')

@app.route('/coverpage')
def coverpage():
    return render_template('coverpage.html')

@app.route('/edit_candidate')
def edit_candidate():
    return render_template('edit_candidate.html')

@app.route('/faq')
def faq():
    return render_template('faq.html')

@app.route('/profile_updated_successfully')
def profile_updated_successfully():
    return render_template('profile_updated_successfully.html')

@app.route('/results')
def results():
    return render_template('results.html')

@app.route('/vote_casted_successfully')
def vote_casted_successfully():
    return render_template('vote_casted_successfully.html')

@app.route('/voter_dashboard')
def voter_dashboard():
    return render_template('voter_dashboard.html')

@app.route('/voting_shedule')
def voting_shedule():
    return render_template('voting_shedule.html')

@app.route('/withdraw_candidate')
def withdraw_candidate():
    return render_template('withdraw_candidate.html')


if __name__ == '__main__':
    app.run(debug=True)