# import streamlit as st
# from time import sleep

# st.set_page_config(layout="centered", page_title="Menti Check Login")

# if "logged_in" not in st.session_state:
#     st.session_state.logged_in = False

# if st.session_state.logged_in:
#     st.switch_page("pages/app.py")

# # Tampilan utama aplikasi
# st.title("Welcome to Menti Check")
# st.write("Please log in to continue (username `admin`, password `admin`).")            

# # Halaman Login
# with st.form("login_form"):
#     username = st.text_input("Username", placeholder="admin")
#     password = st.text_input("Password", type="password", placeholder="admin")
#     submitted = st.form_submit_button("Log in", type="primary")

#     if submitted:
#         # Check for the hardcoded credentials
#         if username == "admin" and password == "admin":
#             st.session_state.logged_in = True
#             st.success("Login successful! Redirecting...")
#             sleep(1)  # Brief pause to show the message
#             st.switch_page("pages/app.py")
#         else:
#             st.error("Incorrect username or password. Please try again.")