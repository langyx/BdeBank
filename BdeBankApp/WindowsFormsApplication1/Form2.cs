using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Net;
using System.Web.Script.Serialization;

namespace WindowsFormsApplication1
{
    public partial class Connexion : Form
    {
        private const string baseURL = "http://149.202.50.198:80/api/";
        public WebClient client = new WebClient();

        public Connexion()
        {
           InitializeComponent();
           this.textBox1.Text = "@epitech.eu";
        }

        private void Form2_Load(object sender, EventArgs e)
        {

        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void label2_Click(object sender, EventArgs e)
        {

        }

        private void button1_Click(object sender, EventArgs e)
        {
            var data = client.DownloadString(baseURL + "login.php?mail=" + this.textBox1.Text + "&pass=" + this.textBox2.Text);
            Console.WriteLine(baseURL + "login.php?mail=" + this.textBox1.Text + "&pass=" + this.textBox2.Text);
            if (data == "ok")
            {
                BDEBAnk mainApp = new BDEBAnk();
                mainApp.staffUser = this.textBox1.Text;
                mainApp.connexionForm = this;
                mainApp.Show();
                this.textBox1.Text = "";
                this.textBox2.Text = "";
                this.Hide();
            }
            else
            {
                MessageBox.Show("Compte non autorisé !");
            }
        }

        private void textBox1_TextChanged(object sender, EventArgs e)
        {

        }
    }
}
