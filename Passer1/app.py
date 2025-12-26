import pickle
from pathlib import Path

import joblib
import streamlit as st
import numpy as np
from streamlit_option_menu import option_menu
# from time import sleep
import pandas as pd
# import sqlite3
# import openai
import google.generativeai as genai
import sklearn
# print(sklearn.__version__)

# Muat pipeline yang telah disimpan
pipeline = joblib.load('MLMentalHealth-v2.sav', 'rb')

# sidebar for navigation
with st.sidebar:
    selected = option_menu(
        'Mental Health Prediction System',
            [
                'Home Page',
                'Mental Health Prediction',
                'Chat Bot',
                'Articles',
            ],
        icons=['activity', 'heart','chat' ,'book'],
        default_index=0)
    # logout = st.button("Log out")

# if 'logged_in' not in st.session_state or not st.session_state.logged_in:
#     st.warning("You must log in first.")
#     st.stop()

# Handle logout
# if logout:
#     st.session_state.logged_in = False
#     st.info("Logged out successfully!")
#     sleep(0.5)
#     st.switch_page("login.py")


# api key gemini
genai.configure(api_key=st.secrets["GEMINI_API_KEY"])
model = genai.GenerativeModel('gemini-1.5-flash')

# Input fields
if selected == 'Mental Health Prediction':
    # Input Gender
    st.title('Prediksi Diagnosis Kesehatan Mental')
    Gender = st.selectbox("Pilih jenis kelamin Anda",
        ("Laki-laki", "Perempuan"),
        index=0)
    if Gender == "Laki-laki":
        Gender = 1
    else:
        Gender = 0
    
    # Input Usia
    Age = st.number_input('Usia', min_value=0)

    # Input Tahun Studi
    Year = st.selectbox("Tahun ke berapa Anda sekarang?",
        ("Tahun 1", "Tahun 2", "Tahun 3", "Tahun 4"))
    if Year == "Tahun 1":
        Year = 0
    elif Year == "Tahun 2":
        Year = 1
    elif Year == "Tahun 3":
        Year = 2
    else:
        Year = 3   

    # Input IPK
    CGPA = st.number_input('IPK', min_value=0.0, max_value=4.0, format="%.2f")

    # Status Pernikahan
    Marital_Status = st.selectbox('Apakah Anda sudah menikah?',
        ("Ya", "Tidak"))
    if Marital_Status == "Ya":
        Marital_Status = 1
    else:
        Marital_Status = 0

    # Input Depresi
    Depression = st.selectbox('Apakah Anda mengalami depresi?',
        ("Ya", "Tidak"))
    if Depression == "Ya":
        Depression = 1
    else:
        Depression = 0

    # Input Kecemasan
    Anxiety = st.selectbox('Apakah Anda mengalami kecemasan?',
        ("Ya", "Tidak"))
    if Anxiety == "Ya":
        Anxiety = 1
    else:
        Anxiety = 0

    # Input Serangan Panik
    Panic_Attack = st.selectbox('Apakah Anda mengalami serangan panik?',
        ("Ya", "Tidak"))
    if Panic_Attack == "Ya":
        Panic_Attack = 1
    else:
        Panic_Attack = 0
            
    # Prediksi saat tombol ditekan
    if st.button('Prediksi'):
        # Gabungkan input ke dalam satu dataframe
        input_data = pd.DataFrame([[Gender, Age, Year, CGPA, Marital_Status, Depression, Anxiety, Panic_Attack]],
                                columns=['Gender', 'Age', 'Year', 'CGPA', 'Marital_Status', 'Depression', 'Anxiety', 'Panic_Attack'])
        
        # Lakukan prediksi
        mental_predic = pipeline.predict(input_data)
        if mental_predic == 0:
            st.text("Anda tidak mengalami gangguan kesehatan mental apa pun.")
        else:
            st.text("Anda mengalami gangguan kesehatan mental.\nMohon jaga diri Anda dengan berkonsultasi ke dokter.")


# #Home Page
# elif selected == 'Home Page':
#     st.title("Welcome to Menti Check")
#     st.write("""
#     The aim of this project is to help individuals understand the seriousness of their mental health condition and provide guidance on whether they need professional help or not. This application can be used by students and college students.""")
#     st.image("https://workplacedna.net/application/files/9016/1218/7136/Bipolar.jpg", caption='World Mental Health Day')
#     st.markdown(""" <style> .font {
#             font-size:35px ; font-family: 'Times New Roman'; color: #11111; font-weight: bold; text-align: center} 
#             .center-text{
#                 text-align: center; 
#     }
#             }
#             </style> """, unsafe_allow_html=True)
#     st.markdown('<p class="font">Our Mission</p>', unsafe_allow_html=True)
#     st.markdown('<p class="center-text">Recognized as a transparent and trustworthy nonprofit provider of quality health information.</p>', unsafe_allow_html=True)
    
#     row1 = st.columns(2)
#     row2 = st.columns(2)
#     missions = [
#         {
#             "title": "Guidance you can trust",
#             "image": "https://www.helpguide.org/wp-content/uploads/Frame-13794.png",
#             "text": "Find trustworthy information about mental health and wellness that you can use to make better decisions."
#         },
#         {
#             "title": "Skills for life success",
#             "image": "https://www.helpguide.org/wp-content/uploads/Frame-13795-1.png",
#             "text": "Build skills to manage your emotions, strengthen your relationships, and cope with difficult situations."
#         },
#         {
#             "title": "Strategies to feel better",
#             "image": "https://www.helpguide.org/wp-content/uploads/Frame-13791.png",
#             "text": "Learn how to improve your mental health and well-being—and help your friends and family do the same."
#         },
#         {
#             "title": "Support you can rely on",
#             "image": "https://www.helpguide.org/wp-content/uploads/Frame-13793.png",
#             "text": "As a free online resource, we’re here for you, day or night, whenever you need guidance, encouragement, or support."
#         }
#     ]
    
#     for i, col in enumerate(row1 + row2):
        
#         if i < len(missions):
#             with col:
#                 st.image(missions[i]["image"], width=120)
#                 st.subheader(missions[i]["title"])
#                 st.write(missions[i]["text"])
#         else:
#             col.empty()

# INDO HOME PAGE
# Halaman Beranda
elif selected == 'Home Page':
    # st.title("Selamat Datang di Menti Check")
    # st.write("""
    # Tujuan dari proyek ini adalah untuk membantu individu memahami tingkat keparahan kondisi kesehatan mental mereka dan memberikan panduan apakah mereka perlu bantuan profesional atau tidak. Aplikasi ini dapat digunakan oleh pelajar dan mahasiswa.
    # """)
    # st.image("https://workplacedna.net/application/files/9016/1218/7136/Bipolar.jpg", caption='Hari Kesehatan Mental Sedunia')
    
    st.markdown("""
        <div style='text-align:center;'>
            <h1 style='color: #f75c5c;'>🧠 Selamat Datang di <span style='color:white;'>Menti Check</span></h1>
            <p style='color:#ccc; font-size:18px; max-width:700px; margin:auto;'>
                Aplikasi ini membantu Anda memahami kondisi kesehatan mental Anda dan memberikan arahan apakah perlu bantuan profesional. Cocok digunakan oleh pelajar dan mahasiswa.
            </p>
        </div>
    """, unsafe_allow_html=True)
    
    st.markdown("<h3 style='text-align:center;'>Misi Kami</h3>", unsafe_allow_html=True)
    st.markdown("<p style='text-align:center; color:#ccc;'>Diakui sebagai penyedia informasi kesehatan berkualitas yang transparan dan terpercaya.</p>", unsafe_allow_html=True)

    row1 = st.columns(2)
    row2 = st.columns(2)

    missions = [
        {
            "title": "Panduan yang Dapat Dipercaya",
            "image": "https://www.helpguide.org/wp-content/uploads/Frame-13794.png",
            "text": "Temukan informasi terpercaya tentang kesehatan mental dan kebugaran yang dapat Anda gunakan untuk membuat keputusan yang lebih baik."
        },
        {
            "title": "Keterampilan untuk Sukses Hidup",
            "image": "https://www.helpguide.org/wp-content/uploads/Frame-13795-1.png",
            "text": "Bangun keterampilan untuk mengelola emosi Anda, memperkuat hubungan, dan menghadapi situasi sulit."
        },
        {
            "title": "Strategi untuk Merasa Lebih Baik",
            "image": "https://www.helpguide.org/wp-content/uploads/Frame-13791.png",
            "text": "Pelajari cara meningkatkan kesehatan mental dan kesejahteraan Anda—dan bantu teman serta keluarga Anda melakukan hal yang sama."
        },
        {
            "title": "Dukungan yang Bisa Diandalkan",
            "image": "https://www.helpguide.org/wp-content/uploads/Frame-13793.png",
            "text": "Sebagai sumber daya daring gratis, kami hadir untuk Anda, siang atau malam, kapan pun Anda membutuhkan panduan, dorongan, atau dukungan."
        }
    ]
    
    for i, col in enumerate(row1 + row2):
        if i < len(missions):
            with col:
                st.image(missions[i]["image"], width=120)
                st.subheader(missions[i]["title"])
                st.write(missions[i]["text"])
        else:
            col.empty()


elif selected == 'Chat Bot':
    if "messages" not in st.session_state:
        st.session_state.messages = [
            {"role": "user", "parts": [
                "Anda adalah konsultan medis di bidang kesehatan mental. Anda akan membantu pengguna dengan pertanyaan atau keluhan terkait kesehatan mental mereka. Jangan menjawab pertanyaan yang tidak relevan dengan kesehatan mental.",
                ]}
        ]

    st.title("Apa yang dapat saya bantu hari ini?")

    for message in st.session_state.messages[1:]:
        with st.chat_message(message["role"]):
            st.markdown("".join(message["parts"]))

    # Input dari pengguna
    prompt = st.chat_input("Silakan ketik pertanyaan atau keluhan Anda di sini...")
    if prompt:
        with st.chat_message("user"):
            st.markdown(prompt)
        st.session_state.messages.append({"role": "user", "parts": [prompt]})

        with st.chat_message("assistant", avatar="👩🏻‍⚕️"):
            message_placeholder = st.empty()
            full_response = ""

            chat = model.start_chat(history=st.session_state.messages)
            response = chat.send_message(prompt, stream=True)

            for chunk in response:
                full_response += chunk.text
                message_placeholder.markdown(full_response + "▌")

            message_placeholder.markdown(full_response)
            st.session_state.messages.append({"role": "model", "parts": [full_response]})
#--------articles-------#
elif selected == 'Articles':
    st.title("Artikel Kesehatan Mental")
    st.write("""Berikut adalah beberapa artikel yang dapat membantu Anda memahami lebih lanjut tentang kesehatan mental dan cara mengelolanya:""")
    
    articles = [
        {
            "title": "Kesehatan Mental Bukan Sekedar Trend: Saatnya Serius Menata Dukungan Psikologis di Indonesia",
            "description": "Artikel opini yang menekankan bahwa kesadaran soal kesehatan mental di Indonesia belum diiringi dengan layanan yang memadai. Mengkritik kurangnya akses ke psikolog, stigma sosial, dan minimnya dukungan kebijakan dari pemerintah",
            "link": "https://www.melintas.id/opini/346124379/kesehatan-mental-bukan-sekedar-trend-saatnya-serius-menata-dukungan-psikologis-di-indonesia",
            "image": "https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=400&h=200&q=80"
        },
        {
            "title": "Cara Mengelola Stres Akademik pada Mahasiswa",
            "description": "Tips dan strategi praktis untuk mengatasi tekanan akademik, mengatur waktu dengan baik, dan menjaga keseimbangan antara belajar dan kehidupan pribadi.",
            "link": "https://dit-mawa.upi.edu/tips-cara-mengatasi-stres-akademik/",
            "image": "https://cianjurkuy.id/wp-content/uploads/2024/03/388.jpg"
        },
        {
            "title": "Mengenali Gejala Depresi dan Kecemasan",
            "description": "Informasi tentang tanda-tanda awal depresi dan kecemasan, kapan harus mencari bantuan profesional, serta pengaruh pola makan terhadap kesehatan mental. Artikel ini juga menyarankan langkah-langkah pencegahan dan sumber daya yang tersedia untuk mendapatkan dukungan.",
            "link": "https://ciputrahospital.com/depresi-dan-kecemasan-gangguan-kesehatan-mental-yang-perlu-kita-waspadai/",
            "image": "https://images.unsplash.com/photo-1573496359142-b8d87734a5cd?auto=format&fit=crop&w=400&h=200&q=80"
        },
        {
            "title": "Teknik Relaksasi dan Meditasi untuk Kesehatan Mental",
            "description": "Berbagai teknik relaksasi yang terbukti efektif untuk mengurangi stres, kecemasan, dan meningkatkan kesejahteraan mental secara keseluruhan.",
            "link": "https://www.mindful.org/how-to-meditate/",
            "image": "https://images.unsplash.com/photo-1544367563-00fcf361f235?auto=format&fit=crop&w=400&h=200&q=80"
        },
        {
            "title": "Membangun Ketahanan Mental (Mental Resilience)",
            "description": "Cara membangun kemampuan untuk beradaptasi dengan menghadapi tantangan, memulihkan diri dari kesulitan, dan tumbuh lebih kuat dari pengalaman sulit.",
            "link": "https://positivepsychology.com/building-mental-resilience/",
            "image": "https://images.unsplash.com/photo-1507835661603-d880890fc57d?auto=format&fit=crop&w=400&h=200&q=80"
        },
        {
            "title": "Kesehatan Mental di Lingkungan Kampus",
            "description": "Panduan untuk menjaga kesehatan mental saat berada di lingkungan kampus yang penuh tekanan, termasuk cara membangun hubungan sosial yang sehat.",
            "link": "https://www.verywellmind.com/college-student-mental-health-4158297",
            "image": "https://images.unsplash.com/photo-1523580494864-9de66ac47b67?auto=format&fit=crop&w=400&h=200&q=80"
        },
        {
            "title": "Ketika Harus Mencari Bantuan Profesional",
            "description": "Tanda-tanda kapan perlu mencari bantuan dari tenaga kesehatan mental profesional dan bagaimana cara mengakses layanan tersebut.",
            "link": "https://www.psychologytoday.com/us/basics/therapy",
            "image": "https://images.unsplash.com/photo-1519494026892-80bb41fb7d0a?auto=format&fit=crop&w=400&h=200&q=80"
        },
        {
            "title": "Gaya Hidup Sehat untuk Kesehatan Mental",
            "description": "Hubungan antara pola makan, olahraga, tidur, dan kesehatan mental, serta tips untuk menjaga keseimbangan antara semua aspek ini.",
            "link": "https://www.health.harvard.edu/mind-and-mood/regular-exercise-can-bolster-your-mental-health",
            "image": "https://images.unsplash.com/photo-1511688878353-172e300f39d5?auto=format&fit=crop&w=400&h=200&q=80"
        }
    ]
    # Display articles
    for i, article in enumerate(articles):
        st.image(article["image"], caption=article["title"], use_container_width=True)
        st.subheader(article["title"])
        st.write(article["description"])
        st.markdown(f"[Baca selengkapnya]({article['link']})")
        if i < len(articles) - 1:  # Add a separator except for the last article
            st.markdown("---")
