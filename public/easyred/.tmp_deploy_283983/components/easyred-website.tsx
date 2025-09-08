"use client"

import { useState, useEffect } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"
import { Checkbox } from "@/components/ui/checkbox"
import { Badge } from "@/components/ui/badge"
import {
  Code,
  Database,
  Palette,
  Server,
  Bot,
  Box,
  Film,
  Brush,
  ArrowRight,
  Lock,
  Mail,
  Phone,
  Grid,
  List,
} from "lucide-react"

export default function EasyredWebsite() {
  const [isScrolled, setIsScrolled] = useState(false)
  const [packagesLayout, setPackagesLayout] = useState<"rows" | "boxes">("rows")

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 50)
    }
    window.addEventListener("scroll", handleScroll)
    return () => window.removeEventListener("scroll", handleScroll)
  }, [])

  const services = [
    {
      icon: Code,
      title: "Full-Stack Development",
      description: "Laravel, Filament, APIs - complete web solutions",
    },
    {
      icon: Database,
      title: "Backend API Specialist",
      description: "Secure, scalable data architecture",
    },
    {
      icon: Palette,
      title: "Frontend & UX Specialist",
      description: "React/Vue, dashboards, user experiences",
    },
    {
      icon: Server,
      title: "DevOps & Hosting",
      description: "Servers, CI/CD, security infrastructure",
    },
    {
      icon: Bot,
      title: "AI & Automation",
      description: "OpenAI integration, workflow automation",
    },
    {
      icon: Box,
      title: "3D / Unreal",
      description: "Real-time, AR/VR, product animation",
    },
    {
      icon: Film,
      title: "Film & Post",
      description: "Shooting, editing, color, VFX",
    },
    {
      icon: Brush,
      title: "Design & Branding",
      description: "UI/UX, brand systems, visual identity",
    },
  ]

  const packages = [
    {
      title: "Full-Stack Development",
      subtitle: "(Recommended)",
      dayRate: 1050,
      isRecommended: true,
    },
    {
      title: "Backend API Specialist",
      dayRate: 950,
    },
    {
      title: "Frontend & UX Specialist",
      dayRate: 900,
    },
    {
      title: "DevOps & Hosting",
      dayRate: 1000,
    },
    {
      title: "AI & Automation Engineer",
      dayRate: 1200,
    },
    {
      title: "3D / Unreal Developer",
      dayRate: 1300,
    },
    {
      title: "Film & Media Production",
      dayRate: 1400,
    },
  ]

  const calculatePrice = (dayRate: number, days: number) => {
    let discount = 0
    if (days === 5) discount = 0.1
    else if (days === 10) discount = 0.15
    else if (days === 20) discount = 0.2

    return Math.round(dayRate * days * (1 - discount))
  }

  const processSteps = [
    {
      title: "Scope",
      description: "Define goals and deliverables",
    },
    {
      title: "Assign",
      description: "We assign the right specialists",
    },
    {
      title: "Build",
      description: "Deliver in day packs, transparently",
    },
    {
      title: "Review",
      description: "Iterate fast, ship reliably",
    },
  ]

  const techStack = [
    "Laravel",
    "Filament",
    "PHP 8.x",
    "MySQL/MariaDB",
    "Redis",
    "React/Vue",
    "Tailwind",
    "Docker",
    "Nginx/Apache",
    "AWS",
    "Vercel",
    "Unreal Engine",
    "Blender",
    "Adobe Suite",
    "OpenAI",
  ]

  return (
    <div className="min-h-screen bg-[#F7F7F8]">
      {/* Navigation */}
      <nav
        className={`fixed top-0 w-full z-50 transition-all duration-300 ${
          isScrolled ? "bg-white/80 backdrop-blur-md shadow-sm" : "bg-transparent"
        }`}
      >
        <div className="max-w-6xl mx-auto px-6 md:px-10 py-4">
          <div className="flex items-center justify-between">
            <div className="text-2xl font-bold">
              <span className="text-[#A64512]">easy</span>
              <span className="text-[#1A1A1A]">red.</span>
            </div>
            <div className="hidden md:flex items-center space-x-8">
              <a href="#home" className="text-[#1A1A1A] hover:text-[#A64512] transition-colors">
                Home
              </a>
              <a href="#services" className="text-[#1A1A1A] hover:text-[#A64512] transition-colors">
                Services
              </a>
              <a href="#packages" className="text-[#1A1A1A] hover:text-[#A64512] transition-colors">
                Packages
              </a>
              <a href="#process" className="text-[#1A1A1A] hover:text-[#A64512] transition-colors">
                Process
              </a>
              <a href="#tech" className="text-[#1A1A1A] hover:text-[#A64512] transition-colors">
                Tech
              </a>
              <a href="#contact" className="text-[#1A1A1A] hover:text-[#A64512] transition-colors">
                Contact
              </a>
            </div>
            <Button
              variant="outline"
              size="sm"
              className="border-[#E9EAEE] text-[#1A1A1A] hover:bg-[#A64512] hover:text-white hover:border-[#A64512] bg-transparent"
            >
              Login
            </Button>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section id="home" className="pt-32 pb-20 px-6 md:px-10 relative overflow-hidden">
        <div className="max-w-6xl mx-auto">
          <div className="absolute top-20 right-20 w-32 h-32 bg-[#A64512]/10 rounded-2xl animate-float" />
          <div
            className="absolute bottom-20 left-20 w-24 h-24 bg-[#1A1A1A]/5 rounded-full animate-float"
            style={{ animationDelay: "2s" }}
          />
          <div
            className="absolute top-40 left-1/3 w-16 h-16 bg-[#E9EAEE] rounded-lg animate-float"
            style={{ animationDelay: "4s" }}
          />

          <div className="text-center max-w-4xl mx-auto">
            <div className="text-4xl md:text-5xl font-bold mb-6 animate-fade-up">
              <span className="text-[#A64512]">easy</span>
              <span className="text-[#1A1A1A]">red.</span>
            </div>
            <h1
              className="text-4xl md:text-6xl font-heading font-bold text-[#1A1A1A] mb-6 animate-fade-up text-balance"
              style={{ animationDelay: "0.2s" }}
            >
              Full-Stack & Creative Development. By the day.
            </h1>
            <p
              className="text-xl md:text-2xl text-[#1A1A1A]/70 mb-8 animate-fade-up text-balance"
              style={{ animationDelay: "0.4s" }}
            >
              Since 1993. We build, host, design, and deliver films & 3D. Every project includes project management.
            </p>
            <div
              className="flex flex-col sm:flex-row gap-4 justify-center animate-fade-up"
              style={{ animationDelay: "0.6s" }}
            >
              <Button
                size="lg"
                className="bg-[#A64512] hover:bg-[#A64512]/90 text-white px-8 py-3 text-lg transition-transform hover:scale-105"
                onClick={() => document.getElementById("packages")?.scrollIntoView({ behavior: "smooth" })}
              >
                Buy Days
                <ArrowRight className="ml-2 h-5 w-5" />
              </Button>
              <Button
                variant="outline"
                size="lg"
                className="px-8 py-3 text-lg transition-transform hover:scale-105 bg-transparent border-[#E9EAEE] text-[#1A1A1A] hover:bg-[#A64512] hover:text-white hover:border-[#A64512]"
              >
                Login
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* Services Section */}
      <section id="services" className="py-20 px-6 md:px-10">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-4xl font-heading font-bold text-[#1A1A1A] mb-4">What we deliver</h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {services.map((service, index) => (
              <Card
                key={index}
                className="border-[#E9EAEE] hover:shadow-lg transition-all duration-300 hover:-translate-y-1 bg-white"
              >
                <CardContent className="p-6 text-center">
                  <service.icon className="h-12 w-12 text-[#A64512] mx-auto mb-4" />
                  <h3 className="font-heading font-semibold text-lg mb-2 text-[#1A1A1A]">{service.title}</h3>
                  <p className="text-[#1A1A1A]/70 text-sm">{service.description}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Packages Section */}
      <section id="packages" className="py-20 px-6 md:px-10 bg-white">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-4xl font-heading font-bold text-[#1A1A1A] mb-4">Packages & Pricing</h2>
            <p className="text-xl text-[#1A1A1A]/70">All projects include Project Management. Buy days, not hours.</p>

            <div className="flex items-center justify-center gap-2 mt-6">
              <Button
                variant={packagesLayout === "boxes" ? "default" : "outline"}
                size="sm"
                onClick={() => setPackagesLayout("boxes")}
                className="bg-[#1A1A1A] text-white hover:bg-[#A64512] border-[#E9EAEE]"
              >
                <Grid className="h-4 w-4 mr-2" />
                Boxes
              </Button>
              <Button
                variant={packagesLayout === "rows" ? "default" : "outline"}
                size="sm"
                onClick={() => setPackagesLayout("rows")}
                className="bg-[#1A1A1A] text-white hover:bg-[#A64512] border-[#E9EAEE]"
              >
                <List className="h-4 w-4 mr-2" />
                Rows
              </Button>
            </div>
          </div>

          {packagesLayout === "boxes" ? (
            // Original card layout
            <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
              {packages.map((pkg, index) => (
                <Card
                  key={index}
                  className={`border-[#E9EAEE] hover:shadow-lg transition-all duration-300 bg-white ${
                    pkg.isRecommended ? "ring-2 ring-[#A64512]" : ""
                  }`}
                >
                  <CardContent className="p-6">
                    <div className="flex items-center justify-between mb-4">
                      <div>
                        <h3 className="font-heading font-semibold text-lg text-[#1A1A1A]">{pkg.title}</h3>
                        {pkg.subtitle && <span className="text-[#A64512] text-sm">{pkg.subtitle}</span>}
                      </div>
                      {pkg.isRecommended && <Badge className="bg-[#A64512] text-white">Recommended</Badge>}
                    </div>

                    <div className="space-y-3 mb-6">
                      <div className="flex justify-between items-center">
                        <span className="text-[#1A1A1A]">1 Day</span>
                        <span className="font-semibold text-[#1A1A1A]">€{pkg.dayRate.toLocaleString()}</span>
                      </div>
                      <div className="flex justify-between items-center">
                        <span className="text-[#1A1A1A]">5 Days</span>
                        <span className="font-semibold text-[#1A1A1A]">
                          €{calculatePrice(pkg.dayRate, 5).toLocaleString()}{" "}
                          <span className="text-sm text-[#A64512]">(10% off)</span>
                        </span>
                      </div>
                      <div className="flex justify-between items-center">
                        <span className="text-[#1A1A1A]">10 Days</span>
                        <span className="font-semibold text-[#1A1A1A]">
                          €{calculatePrice(pkg.dayRate, 10).toLocaleString()}{" "}
                          <span className="text-sm text-[#A64512]">(15% off)</span>
                        </span>
                      </div>
                      <div className="flex justify-between items-center border-t border-[#E9EAEE] pt-2">
                        <span className="text-[#1A1A1A] flex items-center gap-2">
                          20 Days
                          {index === 0 && <Badge className="ml-2 text-xs bg-[#A64512] text-white">BEST VALUE</Badge>}
                        </span>
                        <span className="font-bold text-lg text-[#1A1A1A]">
                          €{calculatePrice(pkg.dayRate, 20).toLocaleString()}{" "}
                          <span className="text-sm text-[#A64512]">(20% off)</span>
                        </span>
                      </div>
                    </div>

                    <Button className="w-full mb-3 transition-transform hover:scale-105 bg-[#1A1A1A] text-white hover:bg-[#A64512] focus:ring-[#A64512]">
                      Buy Days
                    </Button>
                    <p className="text-xs text-[#1A1A1A]/60 text-center">Half-day add-ons are available after login.</p>
                  </CardContent>
                </Card>
              ))}
            </div>
          ) : (
            <div className="bg-white border border-[#E9EAEE] rounded-xl overflow-hidden mb-12">
              {/* Desktop Table */}
              <div className="hidden lg:block">
                <div className="overflow-x-auto">
                  <table className="w-full">
                    <thead className="bg-white border-b border-[#E9EAEE] sticky top-0">
                      <tr>
                        <th className="text-left py-4 px-6 font-bold text-[#1A1A1A]">Package</th>
                        <th className="text-left py-4 px-6 font-bold text-[#1A1A1A]">Day Rate</th>
                        <th className="text-left py-4 px-6 font-bold text-[#1A1A1A]">1 Day</th>
                        <th className="text-left py-4 px-6 font-bold text-[#1A1A1A]">5 Days</th>
                        <th className="text-left py-4 px-6 font-bold text-[#1A1A1A]">10 Days</th>
                        <th className="text-left py-4 px-6 font-bold text-[#1A1A1A]">20 Days</th>
                        <th className="text-left py-4 px-6 font-bold text-[#1A1A1A]">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      {packages.map((pkg, index) => (
                        <tr key={index} className="border-b border-[#E9EAEE] hover:bg-[#F3F4F6] transition-colors">
                          <td className="py-4 px-6">
                            <div className="flex items-center gap-2">
                              <span className="font-semibold text-[#1A1A1A]">{pkg.title}</span>
                              {pkg.isRecommended && (
                                <Badge className="bg-[#A64512] text-white text-xs">Recommended</Badge>
                              )}
                            </div>
                          </td>
                          <td className="py-4 px-6 text-[#1A1A1A]">€{pkg.dayRate.toLocaleString()}</td>
                          <td className="py-4 px-6 text-[#1A1A1A]">€{pkg.dayRate.toLocaleString()}</td>
                          <td className="py-4 px-6 text-[#1A1A1A]">
                            €{calculatePrice(pkg.dayRate, 5).toLocaleString()}
                            <span className="text-xs text-[#A64512] block">(10% off)</span>
                          </td>
                          <td className="py-4 px-6 text-[#1A1A1A]">
                            €{calculatePrice(pkg.dayRate, 10).toLocaleString()}
                            <span className="text-xs text-[#A64512] block">(15% off)</span>
                          </td>
                          <td className="py-4 px-6">
                            <div className="flex items-center gap-2">
                              <span className="text-[#1A1A1A] font-semibold">
                                €{calculatePrice(pkg.dayRate, 20).toLocaleString()}
                              </span>
                              {index === 0 && <Badge className="bg-[#A64512] text-white text-xs">BEST VALUE</Badge>}
                            </div>
                            <span className="text-xs text-[#A64512]">(20% off)</span>
                          </td>
                          <td className="py-4 px-6">
                            <Button
                              size="sm"
                              className="bg-[#1A1A1A] text-white hover:bg-[#A64512] focus:ring-[#A64512]"
                            >
                              Buy Days
                            </Button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>

              {/* Tablet - Horizontal Scroll */}
              <div className="hidden md:block lg:hidden overflow-x-auto">
                <div className="min-w-[800px]">
                  <table className="w-full">
                    <thead className="bg-white border-b border-[#E9EAEE]">
                      <tr>
                        <th className="text-left py-3 px-4 font-bold text-[#1A1A1A] text-sm">Package</th>
                        <th className="text-left py-3 px-4 font-bold text-[#1A1A1A] text-sm">Day Rate</th>
                        <th className="text-left py-3 px-4 font-bold text-[#1A1A1A] text-sm">1 Day</th>
                        <th className="text-left py-3 px-4 font-bold text-[#1A1A1A] text-sm">5 Days</th>
                        <th className="text-left py-3 px-4 font-bold text-[#1A1A1A] text-sm">10 Days</th>
                        <th className="text-left py-3 px-4 font-bold text-[#1A1A1A] text-sm">20 Days</th>
                        <th className="text-left py-3 px-4 font-bold text-[#1A1A1A] text-sm">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      {packages.map((pkg, index) => (
                        <tr key={index} className="border-b border-[#E9EAEE] hover:bg-[#F3F4F6]">
                          <td className="py-3 px-4">
                            <div className="flex flex-col">
                              <span className="font-semibold text-[#1A1A1A] text-sm">{pkg.title}</span>
                              {pkg.isRecommended && (
                                <Badge className="bg-[#A64512] text-white text-xs w-fit mt-1">Rec.</Badge>
                              )}
                            </div>
                          </td>
                          <td className="py-3 px-4 text-[#1A1A1A] text-sm">€{pkg.dayRate.toLocaleString()}</td>
                          <td className="py-3 px-4 text-[#1A1A1A] text-sm">€{pkg.dayRate.toLocaleString()}</td>
                          <td className="py-3 px-4 text-[#1A1A1A] text-sm">
                            €{calculatePrice(pkg.dayRate, 5).toLocaleString()}
                            <span className="text-xs text-[#A64512] block">(10% off)</span>
                          </td>
                          <td className="py-3 px-4 text-[#1A1A1A] text-sm">
                            €{calculatePrice(pkg.dayRate, 10).toLocaleString()}
                            <span className="text-xs text-[#A64512] block">(15% off)</span>
                          </td>
                          <td className="py-3 px-4 text-sm">
                            <div className="flex flex-col">
                              <span className="text-[#1A1A1A] font-semibold">
                                €{calculatePrice(pkg.dayRate, 20).toLocaleString()}
                              </span>
                              {index === 0 && <Badge className="bg-[#A64512] text-white text-xs w-fit">BEST</Badge>}
                              <span className="text-xs text-[#A64512]">(20% off)</span>
                            </div>
                          </td>
                          <td className="py-3 px-4">
                            <Button size="sm" className="bg-[#1A1A1A] text-white hover:bg-[#A64512] text-xs">
                              Buy
                            </Button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>

              {/* Mobile - Stacked Lists */}
              <div className="md:hidden">
                {packages.map((pkg, index) => (
                  <div key={index} className="border-b border-[#E9EAEE] last:border-b-0 p-4">
                    <div className="flex items-center justify-between mb-3">
                      <h3 className="font-semibold text-[#1A1A1A]">{pkg.title}</h3>
                      {pkg.isRecommended && <Badge className="bg-[#A64512] text-white text-xs">Rec.</Badge>}
                    </div>

                    <div className="space-y-2 mb-4">
                      <div className="flex justify-between text-sm">
                        <span className="text-[#1A1A1A]">Day Rate:</span>
                        <span className="text-[#1A1A1A] font-medium">€{pkg.dayRate.toLocaleString()}</span>
                      </div>
                      <div className="flex justify-between text-sm">
                        <span className="text-[#1A1A1A]">1 Day:</span>
                        <span className="text-[#1A1A1A] font-medium">€{pkg.dayRate.toLocaleString()}</span>
                      </div>
                      <div className="flex justify-between text-sm">
                        <span className="text-[#1A1A1A]">5 Days:</span>
                        <span className="text-[#1A1A1A] font-medium">
                          €{calculatePrice(pkg.dayRate, 5).toLocaleString()}
                          <span className="text-[#A64512]"> (10% off)</span>
                        </span>
                      </div>
                      <div className="flex justify-between text-sm">
                        <span className="text-[#1A1A1A]">10 Days:</span>
                        <span className="text-[#1A1A1A] font-medium">
                          €{calculatePrice(pkg.dayRate, 10).toLocaleString()}
                          <span className="text-[#A64512]"> (15% off)</span>
                        </span>
                      </div>
                      <div className="flex justify-between text-sm border-t border-[#E9EAEE] pt-2">
                        <span className="text-[#1A1A1A] flex items-center gap-2">
                          20 Days:
                          {index === 0 && <Badge className="bg-[#A64512] text-white text-xs">BEST</Badge>}
                        </span>
                        <span className="text-[#1A1A1A] font-semibold">
                          €{calculatePrice(pkg.dayRate, 20).toLocaleString()}
                          <span className="text-[#A64512]"> (20% off)</span>
                        </span>
                      </div>
                    </div>

                    <Button
                      className="w-full bg-[#A64512] text-white hover:bg-[#A64512]/90 focus:ring-[#A64512]"
                      size="sm"
                    >
                      Buy Days
                    </Button>
                  </div>
                ))}
              </div>

              <div className="p-4 text-center border-t border-[#E9EAEE] bg-[#F7F7F8]">
                <p className="text-xs text-[#1A1A1A]/60">
                  All projects include Project Management. Half-day add-ons after login.
                </p>
              </div>
            </div>
          )}

          {/* Half-day add-ons gated section */}
          <Card className="border-dashed border-2 border-[#E9EAEE] bg-[#F7F7F8]/50">
            <CardContent className="p-6 text-center">
              <h3 className="font-heading font-semibold text-lg mb-2 text-[#1A1A1A]">Half-Day Add-Ons</h3>
              <p className="text-[#1A1A1A]/70 mb-4">Add a half day (50% of day rate) once you're logged in.</p>
              <Button disabled variant="outline" className="opacity-50 bg-transparent border-[#E9EAEE] text-[#1A1A1A]">
                <Lock className="mr-2 h-4 w-4" />
                Login to add a half day
              </Button>
            </CardContent>
          </Card>
        </div>
      </section>

      {/* Process Section */}
      <section id="process" className="py-20 px-6 md:px-10">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-4xl font-heading font-bold text-[#1A1A1A] mb-4">Our Process</h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            {processSteps.map((step, index) => (
              <div key={index} className="text-center">
                <div className="w-16 h-16 bg-[#A64512] text-white rounded-full flex items-center justify-center text-xl font-bold mx-auto mb-4">
                  {index + 1}
                </div>
                <h3 className="font-heading font-semibold text-lg mb-2 text-[#1A1A1A]">{step.title}</h3>
                <p className="text-[#1A1A1A]/70">{step.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Tech Section */}
      <section id="tech" className="py-20 px-6 md:px-10 bg-white">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-4xl font-heading font-bold text-[#1A1A1A] mb-4">Technologies</h2>
          </div>
          <div className="flex flex-wrap justify-center gap-3">
            {techStack.map((tech, index) => (
              <Badge
                key={index}
                variant="secondary"
                className="px-4 py-2 text-sm bg-[#F7F7F8] text-[#1A1A1A] hover:bg-[#A64512] hover:text-white transition-colors cursor-default border-[#E9EAEE]"
              >
                {tech}
              </Badge>
            ))}
          </div>
        </div>
      </section>

      {/* Contact Section */}
      <section id="contact" className="py-20 px-6 md:px-10">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-4xl font-heading font-bold text-[#1A1A1A] mb-4">Contact</h2>
            <p className="text-lg text-[#1A1A1A]/70 max-w-3xl mx-auto">
              30+ years. Founded by Pieter Liefooghe and Cedric Vandeputte in 1993. Continued by Imhotep Vandeputte.
              Director: Pieter Liefooghe. Our development management expert: Joseph Beaumont.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
              <form className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium mb-2 text-[#1A1A1A]">Name</label>
                    <Input
                      placeholder="Your name"
                      className="bg-white border-[#E9EAEE] focus:border-[#A64512] focus:ring-[#A64512] text-[#1A1A1A] placeholder:text-[#1A1A1A]/50"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium mb-2 text-[#1A1A1A]">Company</label>
                    <Input
                      placeholder="Your company"
                      className="bg-white border-[#E9EAEE] focus:border-[#A64512] focus:ring-[#A64512] text-[#1A1A1A] placeholder:text-[#1A1A1A]/50"
                    />
                  </div>
                </div>
                <div>
                  <label className="block text-sm font-medium mb-2 text-[#1A1A1A]">Email</label>
                  <Input
                    type="email"
                    placeholder="your@email.com"
                    className="bg-white border-[#E9EAEE] focus:border-[#A64512] focus:ring-[#A64512] text-[#1A1A1A] placeholder:text-[#1A1A1A]/50"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-2 text-[#1A1A1A]">Message</label>
                  <Textarea
                    placeholder="Tell us about your project..."
                    rows={4}
                    className="bg-white border-[#E9EAEE] focus:border-[#A64512] focus:ring-[#A64512] text-[#1A1A1A] placeholder:text-[#1A1A1A]/50"
                  />
                </div>
                <div className="flex items-center space-x-2">
                  <Checkbox
                    id="consent"
                    className="border-[#E9EAEE] data-[state=checked]:bg-[#A64512] data-[state=checked]:border-[#A64512]"
                  />
                  <label htmlFor="consent" className="text-sm text-[#1A1A1A]/70">
                    I agree to be contacted by Easyred.
                  </label>
                </div>
                <Button
                  size="lg"
                  className="w-full transition-transform hover:scale-105 bg-[#A64512] hover:bg-[#A64512]/90 text-white"
                >
                  Send inquiry
                </Button>
              </form>
              <p className="text-sm text-[#1A1A1A]/70 mt-4 text-center">B2B only. We plan work in day packs.</p>
            </div>

            <div className="space-y-6">
              <Card className="border-[#E9EAEE] bg-white">
                <CardContent className="p-6">
                  <h3 className="font-heading font-semibold text-lg mb-4 text-[#1A1A1A]">Get in touch</h3>
                  <div className="space-y-3">
                    <div className="flex items-center space-x-3">
                      <Mail className="h-5 w-5 text-[#A64512]" />
                      <a
                        href="mailto:hello@easyred.com"
                        className="text-[#1A1A1A]/70 hover:text-[#A64512] transition-colors"
                      >
                        hello@easyred.com
                      </a>
                    </div>
                    <div className="flex items-center space-x-3">
                      <Phone className="h-5 w-5 text-[#A64512]" />
                      <span className="text-[#1A1A1A]/70">+32 (0) 123 456 789</span>
                    </div>
                  </div>
                </CardContent>
              </Card>

              <Card className="border-[#E9EAEE] bg-white">
                <CardContent className="p-6">
                  <h3 className="font-heading font-semibold text-lg mb-4 text-[#1A1A1A]">The Easyred Team</h3>
                  <div className="space-y-2 text-sm text-[#1A1A1A]/70">
                    <p>SLCIF: B38704755</p>
                    <p>TF-657, 1438627</p>
                    <p>Arona</p>
                    <p>Canary Islands</p>
                    <p>Spain</p>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="py-12 px-6 md:px-10 bg-white border-t border-[#E9EAEE]">
        <div className="max-w-6xl mx-auto">
          <div className="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div className="text-xl font-bold">
              <span className="text-[#A64512]">easy</span>
              <span className="text-[#1A1A1A]">red.</span>
            </div>
            <div className="flex items-center space-x-6 text-sm text-[#1A1A1A]/70">
              <a href="#home" className="hover:text-[#A64512] transition-colors">
                Home
              </a>
              <span>•</span>
              <a href="#services" className="hover:text-[#A64512] transition-colors">
                Services
              </a>
              <span>•</span>
              <a href="#packages" className="hover:text-[#A64512] transition-colors">
                Packages
              </a>
              <span>•</span>
              <a href="#process" className="hover:text-[#A64512] transition-colors">
                Process
              </a>
              <span>•</span>
              <a href="#tech" className="hover:text-[#A64512] transition-colors">
                Tech
              </a>
              <span>•</span>
              <a href="#contact" className="hover:text-[#A64512] transition-colors">
                Contact
              </a>
            </div>
            <div className="text-sm text-[#1A1A1A]/70">© Easyred, 1993–present</div>
          </div>
        </div>
      </footer>
    </div>
  )
}
